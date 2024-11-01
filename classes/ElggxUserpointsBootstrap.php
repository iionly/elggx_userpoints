<?php

use Elgg\DefaultPluginBootstrap;

class ElggxUserpointsBootstrap extends DefaultPluginBootstrap {

	public function activate() {
		$current_version = elgg_get_plugin_setting('version', 'elggx_userpoints');
		$new_version = '5.0.0';

		if (version_compare($current_version, $new_version, '!=')) {
			// Set new version
			$plugin = elgg_get_plugin_from_id('elggx_userpoints');
			$plugin->setSetting('version', $new_version);
		}
	}
}
