<?php

namespace Vivait\BootstrapBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Vivait\BravoBundle\Entity\Area;
use Vivait\BravoBundle\Entity\PostcodeArea;
use Vivait\BravoBundle\Entity\PostcodeDistrict;

class GenerateFixtureCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
          ->setName('generate:fixture')
          ->setDescription('Generates a fixture')
          ->addArgument('query', InputArgument::REQUIRED, 'What query do you want to create a fixture for?')
          ->addOption('chain', null, InputOption::VALUE_NONE, 'If set, it will chain the method calls');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery($input->getArgument('query'));
        $chain = $input->getOption('chain');
        $code  = '';

        foreach ($query->getResult() as $result) {
            $code .= $this->generateAliceFixture($result);
        }

        file_put_contents($this->getContainer()->getParameter("kernel.cache_dir") . '/fixtures.yml', $code);

        $output->writeln($code);
    }

    protected function generateFixture($object, $chain = false)
    {
        $reflection = new \ReflectionObject($object);

        $output = sprintf('$class = new \%s;%s', $reflection->getName(), PHP_EOL);

        $is_first = true;

        foreach ($reflection->getMethods() as $method) {
            if (substr_compare($method->getName(), 'set', 0, 3) === 0) {
                $cut_name = substr($method->getName(), 3);

                try {
                    $getter = $reflection->getMethod('get' . $cut_name);
                } catch (\ReflectionException $e) {
                    continue;
                }

                // There's a getter
                if ($getter) {
                    $value = $getter->invoke($object);

                    if ($value === null || $value === '') {
                        continue;
                    }

                    if (is_object($value)) {
                        $value_reflection = new \ReflectionObject($value);

                        try {
                            $value_reflection->getMethod('__set_state');
                        } catch (\ReflectionException $e) {
                            continue;
                        }
                    }

                    $output .= sprintf(
                      '%s->%s(%s)%s',
                      (($chain && !$is_first) ? "\t" : '$class'),
                      $method->getName(),
                      var_export($value, true),
                      ($chain ? '' : ';') . PHP_EOL
                    );
                    $is_first = false;
                }
            }
        }

        if ($chain) {
            $output = substr($output, 0, strlen($output) - 1) . ';' . PHP_EOL;
        }

        $output .= '$manager->persist($class);' . PHP_EOL . PHP_EOL;

        return $output;
    }

    protected function generateAliceFixture($object, $name = 'district')
    {
        $reflection = new \ReflectionObject($object);
        $array      = [];

        $entity = $reflection->getName();

        $key = $reflection->getProperty($name);
        $key->setAccessible(true);
        $key = $key->getValue($object);

        foreach ($reflection->getProperties() as $property) {
            if (in_array($property->getName(), ['id', 'areas'])) {
                continue;
            }

            $property->setAccessible(true);
            $value = $property->getValue($object);
            $value = $this->getYAMLValue($value);

            if ($value === null) {
                continue;
            }

            $array[(string)$key][$property->getName()] = $value;
        }

        return Yaml::dump([$entity => $array], 3);
    }

    protected function getYAMLValue($value)
    {
        if (is_array($value) || $value instanceOf \Traversable) {
            $new_value = [];
            foreach ($value as $foreign) {
                if ($foreign instanceOf Area) {
                    return null;
                }
                // TODO: Make this more flexible
                $new_value[] = $this->getYAMLValue($foreign);
            }
            return $new_value;
        //} else if ($value instanceOf PostcodeDistrict) {
        //    return '@' . $value->getDistrict();
        //} else if ($value instanceOf Area) {
        //    return null;
        //    return '@' . $value->getName();
        //} else if ($value instanceOf PostcodeArea) {
        //    return '@' . $value->getArea();
        } else if (is_object($value)) {
            return '@' . $value->getId();
        } else {
            return $value;
        }
    }
}
