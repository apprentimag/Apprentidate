<?php
/** 
 * MINZ - Copyright 2011 Marien Fressinaud
 * Sous licence AGPL3 <http://www.gnu.org/licenses/>
*/

/**
 * La classe Paginator permet de gérer la pagination de l'application facilement
 */
class Paginator {
	/**
	 * $items tableau des éléments à afficher/gérer
	 */
	private $items = array ();

	/**
	 * $nbItemsPerPage le nombre d'éléments par page
	 */
	private $nbItemsPerPage = 10;

	/**
	 * $currentPage page actuelle à gérer
	 */
	private $currentPage = 1;

	/**
	 * $nbPage le nombre de pages de pagination
	 */
	private $nbPage = 1;

	/**
	 * Constructeur
	 * @param $items les éléments à gérer
	 */
	public function __construct ($items) {
		$this->_items ($items);
		$this->_nbItemsPerPage ($this->nbItemsPerPage);
		$this->_currentPage ($this->currentPage);
	}

	/**
	 * Permet d'afficher la pagination
	 * @param $view nom du fichier de vue situé dans /app/views/helpers/
	 * @param $getteur variable de type $_GET[] permettant de retrouver la page
	 */
	public function render ($view, $getteur) {
		$view = APP_PATH . '/views/helpers/'.$view;
		
		if (file_exists ($view)) {
			include ($view);
		}
	}

	/**
	 * Permet de retrouver la page d'un élément donné
	 * @param $item l'élément à retrouver
	 * @return la page à laquelle se trouve l'élément (false si non trouvé)
	 */
	public function pageByItem ($item) {
		$page = false;
		$i = 0;

		do {
			if ($item == $this->items[$i]) {
				$page = ceil (($i + 1) / $this->nbItemsPerPage);
			}

			$i++;
		} while (!$page && $i < count ($this->items));

		return $page;
	}

	/**
	 * Permet de retrouver la position d'un élément donné (à partir de 0)
	 * @param $item l'élément à retrouver
	 * @return la position à laquelle se trouve l'élément (false si non trouvé)
	 */
	public function positionByItem ($item) {
		$find = false;
		$i = 0;

		do {
			if ($item == $this->items[$i]) {
				$find = true;
			} else {
				$i++;
			}
		} while (!$find && $i < count ($this->items));

		return $i;
	}

	/**
	 * Permet de récupérer un item par sa position
	 * @param $pos la position de l'élément
	 * @return l'item situé à $pos (dernier item si $pos<0, 1er si $pos>=count($items))
	 */
	public function itemByPosition ($pos) {
		if ($pos < 0) {
			$pos = count ($this->items) - 1;
		}
		if ($pos >= count($this->items)) {
			$pos = 0;
		}

		return $this->items[$pos];
	}

	/**
	 * GETTEURS
	 */
	/**
	 * @param $all si à true, retourne tous les éléments sans prendre en compte la pagination
	 */
	public function items ($all = false) {
		$array = array ();
		$nbItems = count ($this->items);

		if ($nbItems <= $this->nbItemsPerPage || $all) {
			$array = $this->items;
		} else {
			$i = ($this->currentPage - 1) * $this->nbItemsPerPage;
			$counter = 0;

			while ($counter < $this->nbItemsPerPage && $i < $nbItems) {
				$array[] = $this->items[$i];
				$counter++;
				$i++;
			}
		}

		return $array;
	}
	public function nbItemsPerPage  () {
		return $this->nbItemsPerPage;
	}
	public function currentPage () {
		return $this->currentPage;
	}
	public function nbPage () {
		return $this->nbPage;
	}

	/**
	 * SETTEURS
	 */
	public function _items ($items) {
		if (is_array ($items)) {
			$this->items = $items;
		}
		
		$this->_nbPage ();
	}
	public function _nbItemsPerPage ($nbItemsPerPage) {
		if ($nbItemsPerPage > count ($this->items)) {
			$nbItemsPerPage = count ($this->items);
		}
		if ($nbItemsPerPage < 0) {
			$nbItemsPerPage = 0;
		}

		$this->nbItemsPerPage = $nbItemsPerPage;
		$this->_nbPage ();
	}
	public function _currentPage ($currentPage) {
		if($currentPage < 1) {
			$currentPage = 1;
		}
		if ($currentPage > $this->nbPage) {
			$currentPage = $this->nbPage;
		} 

		$this->currentPage = $currentPage;
	}
	private function _nbPage () {
		if ($this->nbItemsPerPage > 0) {
			$this->nbPage = ceil (count ($this->items) / $this->nbItemsPerPage);
		}
	}
}
