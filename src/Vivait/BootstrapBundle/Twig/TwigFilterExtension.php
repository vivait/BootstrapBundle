<?php
namespace Vivait\BootstrapBundle\Twig;

use \Twig_Filter_Function;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;

class TwigFilterExtension extends \Twig_Extension {
    public function getName() {
        return 'twig_extension';
    }

	
    public function getFilters() {
        return array(
			new \Twig_SimpleFilter('yesno', array($this, 'yesnoFilter')),
			new \Twig_SimpleFilter('yesnoicon', array($this, 'yesnoiconFilter')),
			new \Twig_SimpleFilter('gender', array($this, 'genderFilter')),
			new \Twig_SimpleFilter('money', array($this, 'moneyFilter')),
			new \Twig_SimpleFilter('printr', array($this, 'printrFilter')),
			new \Twig_SimpleFilter('file_exists', array($this, 'file_exists'))
        );
    }
	
	public function yesnoFilter($boolean) {
		return $boolean?'Yes':'No';
	}
	
	public function yesnoiconFilter($boolean) {
		return $boolean?'<i class="text-success glyphicon glyphicon-ok"></i>':'<i class="text-danger glyphicon glyphicon-remove"></i>';
	}
	
	public function genderFilter($sex) {
		if($sex=='M') {
			return 'Male';
		} elseif($sex=='F') {
			return 'Female';
		} else {
			return 'Unknown';
		}
	}

	public function moneyFilter($float,$currency = NULL) {
		$nf = new NumberFormatter('en', NumberFormatter::CURRENCY);
		if($currency) {
			return $nf->formatCurrency($float,$currency);
		} else {
			return number_format($float,2,'.',',');
		}


	}

	public function file_exists($file) {
		return file_exists($file);
	}
	
}
?>
