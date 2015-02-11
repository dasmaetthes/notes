<?php
/**
 * ownCloud - Notes
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 * @copyright Bernhard Posselt 2012, 2014
 */

namespace OCA\Notes\Controller;

use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\IConfig;

use OCA\Notes\Service\NotesService;
use OCA\Notes\Service\NoteDoesNotExistException;


class PageController extends Controller {

	private $notesService;
	private $settings;
	private $userId;

	public function __construct($AppName,
	                            IRequest $request,
	                            NotesService $notesService,
	                            IConfig $settings,
	                            $UserId){
		parent::__construct($AppName, $request);
		$this->notesService = $notesService;
		$this->settings = $settings;
		$this->userId = $UserId;
	}


	/**
	 * ATTENTION!!!
	 * The following comments turn off security checks
	 * Please look up their meaning in the documentation:
	 * http://doc.owncloud.org/server/master/developer_manual/app/appframework/controllers.html
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		$lastViewedNote = (int) $this->settings->getUserValue($this->userId,
			$this->appName, 'notesLastViewedNote');
		// check if note exists
		try {
			$this->notesService->get($lastViewedNote, $this->userId);
		} catch(NoteDoesNotExistException $ex) {
			$lastViewedNote = 0;
		}

		return $this->render('main', [
			'lastViewedNote' => $lastViewedNote
		]);
	}


}