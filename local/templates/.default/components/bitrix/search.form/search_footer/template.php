<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<div class="col-lg-6">
	<h4><?=GetMessage("TITLE");?></h4>
	<form action="<?=$arResult["FORM_ACTION"]?>">
		<div class="search-form">
			<?if($arParams["USE_SUGGEST"] === "Y"):?><?$APPLICATION->IncludeComponent(
					"bitrix:search.suggest.input",
					"",
					array(
						"NAME" => "q",
						"VALUE" => "",
						"INPUT_SIZE" => 15,
						"DROPDOWN_SIZE" => 10,
					),
					$component, array("HIDE_ICONS" => "Y")
				);?><?else:?><input class="input-seach" type="text" name="q"/><?endif;?>

				<input class="button-seach" name="s" type="submit" value="<?=GetMessage("SEARCH_BUTTON")?>"/>
		</div>
	</form>
</div>
