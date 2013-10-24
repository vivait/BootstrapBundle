<?php
namespace Vivait\BootstrapBundle\Twig;

use \Twig_Filter_Function;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;

class TwigFilterExtension extends \Twig_Extension {
    public function getName() {
        return 'twig_extension';
    }

	
    public function getFilters() {
        return array( ########################CHANGE HERE TOO#########################
			'yesno' => new \Twig_Filter_Method($this, 'yesnoFilter'),
			'yesnoicon' => new \Twig_Filter_Method($this, 'yesnoiconFilter'),
			'gender' => new \Twig_Filter_Method($this, 'genderFilter'),
			'money' => new \Twig_Filter_Method($this, 'moneyFilter'),
			'printr' => new \Twig_Filter_Method($this, 'printrFilter'),
			'file_exists' => new \Twig_Filter_Method($this, 'file_exists'),
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