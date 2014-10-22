<?php

namespace PHPSTORM_META {

	/** @noinspection PhpUnusedLocalVariableInspection */                 // just to have a green code below
	/** @noinspection PhpIllegalArrayKeyTypeInspection */
	$STATIC_METHOD_TYPES = [                                              // we make sections for scopes
		\AbleCore::helper('') => [
			'HTML' instanceof \Drupal\ablecore\Helpers\HTML,
			'Inflector' instanceof \Drupal\ablecore\Helpers\Inflector,
		],
		\AbleCore::hook('') => [
			'menu' instanceof \Drupal\ablecore\HookHelpers\Menu,
			'Menu' instanceof \Drupal\ablecore\HookHelpers\Menu,
			'block_info' instanceof \Drupal\ablecore\HookHelpers\BlockInfo,
			'BlockInfo' instanceof \Drupal\ablecore\HookHelpers\BlockInfo,
			'image_default_styles' instanceof \Drupal\ablecore\HookHelpers\ImageDefaultStyles,
			'ImageDefaultStyles' instanceof \Drupal\ablecore\HookHelpers\ImageDefaultStyles,
			'theme' instanceof \Drupal\ablecore\HookHelpers\Theme,
			'Theme' instanceof \Drupal\ablecore\HookHelpers\Theme,
		]
	];

}
