<?php
/**
 * @copyright Copyright (c) 2016 Bjoern Schiessle <bjoern@schiessle.org>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace OCA\LocationTracker\Controller;


use OCA\LocationTracker\Tracker;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\IUserSession;

class LocationTrackerController extends Controller {

	/** @var Tracker */
	private $tracker;

	/** @var IUserSession */
	private $userSession;

	/**
	 * LocationTrackerController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param Tracker $tracker
	 * @param IUserSession $userSession
	 */
	public function __construct(
		$appName,
		IRequest $request,
		Tracker $tracker,
		IUserSession $userSession
	) {
		parent::__construct($appName, $request);

		$this->tracker = $tracker;
		$this->userSession = $userSession;
	}

	/**
	 * @noAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $_type data type
	 * @param double $lat latitude
	 * @param double $lon longitude
	 * @param string $tid tracker id
	 * @param int $tst timestamp
	 * @return JSONResponse
	 */
	public function store($_type, $lat, $lon, $tid, $tst) {
		$user = $this->userSession->getUser();
		if ($_type === 'location') {
			$this->tracker->storeLocation($user, $tid, $lat, $lon, $tst);
		}

		return new JSONResponse();

	}

}
