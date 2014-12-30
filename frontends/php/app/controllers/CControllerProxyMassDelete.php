<?php
/*
** Zabbix
** Copyright (C) 2001-2014 Zabbix SIA
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

class CControllerProxyMassDelete extends CController {

	protected function checkInput() {
		$fields = array(
			'proxyids' =>		'fatal|array_db:hosts.hostid|required'
		);

		$result = $this->validateInput($fields);

		if (!$result) {
			$this->setResponse(new CControllerResponseFatal());
		}

		return $result;
	}

	protected function checkPermissions() {
		if ($this->getUserType() != USER_TYPE_SUPER_ADMIN) {
			access_deny();
		}

		$proxies = API::Proxy()->get(array(
			'proxyids' => $this->getInput('proxyids'),
			'countOutput' => true
		));

		if ($proxies != count($this->getInput('proxyids'))) {
			access_deny();
		}
	}

	protected function doAction() {
		$result = API::Proxy()->delete($this->getInput('proxyids'));

		$response = new CControllerResponseRedirect('zabbix.php?action=proxy.list&uncheck=1');

		if ($result) {
			$response->setMessageOk(_('Proxy deleted'));
		}
		else {
			$response->setMessageError(_('Cannot delete proxy'));
		}
		$this->setResponse($response);
	}
}
