<?php

declare(strict_types=1);

namespace Ublaboo\DataGrid\Export;

use Nette\Utils\Html;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Traits\TButtonClass;
use Ublaboo\DataGrid\Traits\TButtonIcon;
use Ublaboo\DataGrid\Traits\TButtonText;
use Ublaboo\DataGrid\Traits\TButtonTitle;
use Ublaboo\DataGrid\Traits\TButtonTryAddIcon;

class Export
{

	use TButtonTryAddIcon;
	use TButtonIcon;
	use TButtonClass;
	use TButtonTitle;
	use TButtonText;

	/**
	 * @var callable
	 */
	protected $callback;

	/**
	 * @var bool
	 */
	protected $ajax;

	/**
	 * @var bool
	 */
	protected $filtered;

	/**
	 * @var string|null
	 */
	protected $link;

	/**
	 * @var array
	 */
	protected $columns = [];

	/**
	 * @var DataGrid
	 */
	protected $grid;

	/**
	 * @var string|null
	 */
	protected $confirmDialog = null;

	public function __construct(
		DataGrid $grid,
		string $text,
		callable $callback,
		bool $filtered
	)
	{
		$this->grid = $grid;
		$this->text = $text;
		$this->callback = $callback;
		$this->filtered = (bool) $filtered;
		$this->title = $text;
	}


	public function render(): Html
	{
		$a = Html::el('a', [
			'class' => [$this->class],
			'title' => $this->grid->getTranslator()->translate($this->getTitle()),
			'href' => $this->link,
		]);

		$this->tryAddIcon(
			$a,
			$this->getIcon(),
			$this->grid->getTranslator()->translate($this->getTitle()),
		);

		$a->addText($this->grid->getTranslator()->translate($this->text));

		if ($this->isAjax()) {
			$a->appendAttribute('class', 'ajax');
		}

		if ($this->confirmDialog !== null) {
			$a->setAttribute('data-datagrid-confirm', $this->confirmDialog);
		}

		return $a;
	}


	public function setConfirmDialog(string $confirmDialog): self
	{
		$this->confirmDialog = $confirmDialog;

		return $this;
	}


	/**
	 * Tell export which columns to use when exporting data
	 */
	public function setColumns(array $columns): self
	{
		$this->columns = $columns;

		return $this;
	}


	/**
	 * Get columns for export
	 */
	public function getColumns(): array
	{
		return $this->columns;
	}


	/**
	 * Export signal url
	 */
	public function setLink(string $link): self
	{
		$this->link = $link;

		return $this;
	}


	/**
	 * Tell export whether to be called via ajax or not
	 */
	public function setAjax(bool $ajax = true): self
	{
		$this->ajax = $ajax;

		return $this;
	}


	public function isAjax(): bool
	{
		return $this->ajax;
	}


	/**
	 * Is export filtered?
	 */
	public function isFiltered(): bool
	{
		return $this->filtered;
	}


	/**
	 * Call export callback
	 */
	public function invoke(array $data): void
	{
		($this->callback)($data, $this->grid);
	}

}
