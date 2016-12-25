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


namespace OCA\LocationTracker;


use OCP\IDBConnection;
use OCP\IUser;

/**
 * Class Tracker
 *
 * @group Db
 * @package OCA\LocationTracker
 */
class Tracker {

	/** @var IDBConnection */
	private $dbConnection;

	/** @var string database table to store locations */
	private $dbTable = 'locations';

	/**
	 * Tracker constructor.
	 *
	 * @param IDBConnection $dbConnection
	 */
	public function __construct(IDBConnection $dbConnection) {
		$this->dbConnection = $dbConnection;
	}

	/**
	 * write location to database
	 *
	 * @param IUser $user
	 * @param string $tid
	 * @param double $lat
	 * @param double $lon
	 * @param int $timestamp
	 * @return int
	 * @throws \Exception
	 */
	public function storeLocation(IUser $user, $tid, $lat, $lon, $timestamp) {
		$query = $this->dbConnection->getQueryBuilder();
		$query->insert($this->dbTable)
			->values(
				[
					'uid' => $query->createNamedParameter($user->getUID(), string),
					'lat' => $query->createNamedParameter((string)$lat, string),
					'lon' => $query->createNamedParameter((string)$lon, string),
					'tid' => $query->createNamedParameter($tid, string),
					'timestamp' => $query->createNamedParameter($timestamp, integer)
				]
			);

		$result = $query->execute();

		if ($result) {
			return (int)$this->dbConnection->lastInsertId('*PREFIX*'.$this->dbTable);
		} else {
			throw new \Exception('Internal failure, Could not add location');
		}
	}

}
