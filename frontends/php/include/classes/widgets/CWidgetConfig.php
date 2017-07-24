<?php
/*
** Zabbix
** Copyright (C) 2001-2017 Zabbix SIA
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**/


class CWidgetConfig {

	/**
	 * Return list of all widget types with names.
	 *
	 * @static
	 *
	 * @return array
	 */
	public static function getKnownWidgetTypes() {
		return [
			WIDGET_SYSTEM_STATUS		=> _('System status'),
			WIDGET_ZABBIX_STATUS		=> _('Status of Zabbix'),
			WIDGET_PROBLEMS				=> _('Problems'),
			WIDGET_WEB_OVERVIEW			=> _('Web monitoring'),
			WIDGET_DISCOVERY_STATUS		=> _('Discovery status'),
			WIDGET_HOST_STATUS			=> _('Host status'),
			WIDGET_FAVOURITE_GRAPHS		=> _('Favourite graphs'),
			WIDGET_FAVOURITE_MAPS		=> _('Favourite maps'),
			WIDGET_FAVOURITE_SCREENS	=> _('Favourite screens'),
			WIDGET_CLOCK				=> _('Clock'),
			WIDGET_SYSMAP				=> _('Map'),
			WIDGET_NAVIGATION_TREE		=> _('Map Navigation Tree'),
			WIDGET_URL					=> _('URL'),
			WIDGET_ACTION_LOG			=> _('Action log'),
			WIDGET_DATA_OVERVIEW		=> _('Data overview'),
			WIDGET_TRIG_OVERVIEW		=> _('Trigger overview')
		];
	}

	/**
	 * Get default widget dimensions.
	 *
	 * @static
	 *
	 * @return array
	 */
	private static function getDefaultDimensions()
	{
		// TODO AV: review and accept default dimentions
		return [
			WIDGET_SYSTEM_STATUS		=> ['width' => 6, 'height' => 4],
			WIDGET_ZABBIX_STATUS		=> ['width' => 6, 'height' => 5],
			WIDGET_PROBLEMS				=> ['width' => 6, 'height' => 5],
			WIDGET_WEB_OVERVIEW			=> ['width' => 3, 'height' => 3],
			WIDGET_DISCOVERY_STATUS		=> ['width' => 3, 'height' => 3],
			WIDGET_HOST_STATUS			=> ['width' => 6, 'height' => 4],
			WIDGET_FAVOURITE_GRAPHS		=> ['width' => 2, 'height' => 3],
			WIDGET_FAVOURITE_MAPS		=> ['width' => 2, 'height' => 3],
			WIDGET_FAVOURITE_SCREENS	=> ['width' => 2, 'height' => 3],
			WIDGET_CLOCK				=> ['width' => 3, 'height' => 3],
			WIDGET_SYSMAP				=> ['width' => 9, 'height' => 5],
			WIDGET_NAVIGATION_TREE		=> ['width' => 3, 'height' => 5],
			WIDGET_URL					=> ['width' => 6, 'height' => 5],
			WIDGET_ACTION_LOG			=> ['width' => 6, 'height' => 5],
			WIDGET_DATA_OVERVIEW		=> ['width' => 6, 'height' => 5],
			WIDGET_TRIG_OVERVIEW		=> ['width' => 6, 'height' => 5]
		];
	}

	/**
	 * Return default values for new widgets.
	 *
	 * @static
	 *
	 * @return array
	 */
	public static function getDefaults() {
		$ret = [];
		$dimensions = self::getDefaultDimensions();

		foreach (self::getKnownWidgetTypes() as $type => $name) {
			$ret[$type] = [
				'header' => $name,
				'rf_rate' => self::getDefaultRfRate($type),
				'size' => $dimensions[$type]
			];
		}

		$ret['__unknown'] = [
			'header' => _('Unknown'),
			'rf_rate' => SEC_PER_MIN,
			'size' => ['width' => 2, 'height' => 3]
		];

		return $ret;
	}

	/**
	 * Return default refresh rate for widget type.
	 *
	 * @static
	 *
	 * @param int $type  WIDGET_ constant
	 *
	 * @return int  default refresh rate, "0" for no refresh
	 */
	public static function getDefaultRfRate($type) {
		switch ($type) {
			case WIDGET_SYSTEM_STATUS:
			case WIDGET_PROBLEMS:
			case WIDGET_WEB_OVERVIEW:
			case WIDGET_DISCOVERY_STATUS:
			case WIDGET_HOST_STATUS:
			case WIDGET_ACTION_LOG:
			case WIDGET_DATA_OVERVIEW:
			case WIDGET_TRIG_OVERVIEW:
				return SEC_PER_MIN;

			case WIDGET_ZABBIX_STATUS:
			case WIDGET_FAVOURITE_GRAPHS:
			case WIDGET_FAVOURITE_MAPS:
			case WIDGET_FAVOURITE_SCREENS:
			case WIDGET_NAVIGATION_TREE:
			case WIDGET_CLOCK:
			case WIDGET_SYSMAP:
				return 15 * SEC_PER_MIN;

			case WIDGET_URL:
				return 0;
		}
	}

	/**
	 * Return Form object for widget with provided data.
	 *
	 * @static
	 *
	 * @param string $type          widget type - 'WIDGET_' constant
	 * @param array  $data          array with all widget's fields
	 * @param string $data[<name>]  (optional)
	 *
	 * @return CWidgetForm
	 */
	public static function getForm($type, $data) {
		switch ($type) {
			case WIDGET_CLOCK:
				return new CClockWidgetForm($data);

			case WIDGET_NAVIGATION_TREE:
				return new CNavigationWidgetForm($data);

			case WIDGET_SYSMAP:
				return new CSysmapWidgetForm($data);

			case WIDGET_URL:
				return new CUrlWidgetForm($data);

			case WIDGET_ACTION_LOG:
				return new CActionLogWidgetForm($data);

			case WIDGET_DATA_OVERVIEW:
				return new CDataOverviewWidgetForm($data);

			case WIDGET_TRIG_OVERVIEW:
				return new CTrigOverviewWidgetForm($data);

			case WIDGET_PROBLEMS:
				return new CProblemsWidgetForm($data);

			case WIDGET_WEB_OVERVIEW:
				return new CWebWidgetForm($data);

			case WIDGET_SYSTEM_STATUS:
				return new CSystemWidgetForm($data);

			case WIDGET_HOST_STATUS:
				return new CHostsWidgetForm($data);

			default:
				// TODO VM: delete this case after all widget forms will be created
				return new CWidgetForm($data);
		}
	}
}
