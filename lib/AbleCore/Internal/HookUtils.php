<?php

namespace AbleCore\Internal;

class HookUtils {

	public static function getAbleCoreModules()
	{
		return module_implements('ablecore');
	}

} 
