<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
?>
<?
// echo "<pre>";
// print_r($arResult["SEARCH"]);
// echo "</pre>";
?>
<section id="blog-posts" class="blog-posts section">
	<div class="container">
		<?if($arParams["SHOW_TAGS_CLOUD"] == "Y")
		{
			$arCloudParams = Array(
				"SEARCH" => $arResult["REQUEST"]["~QUERY"],
				"TAGS" => $arResult["REQUEST"]["~TAGS"],
				"CHECK_DATES" => $arParams["CHECK_DATES"],
				"arrFILTER" => $arParams["arrFILTER"],
				"SORT" => $arParams["TAGS_SORT"],
				"PAGE_ELEMENTS" => $arParams["TAGS_PAGE_ELEMENTS"],
				"PERIOD" => $arParams["TAGS_PERIOD"],
				"URL_SEARCH" => $arParams["TAGS_URL_SEARCH"],
				"TAGS_INHERIT" => $arParams["TAGS_INHERIT"],
				"FONT_MAX" => $arParams["FONT_MAX"],
				"FONT_MIN" => $arParams["FONT_MIN"],
				"COLOR_NEW" => $arParams["COLOR_NEW"],
				"COLOR_OLD" => $arParams["COLOR_OLD"],
				"PERIOD_NEW_TAGS" => $arParams["PERIOD_NEW_TAGS"],
				"SHOW_CHAIN" => "N",
				"COLOR_TYPE" => $arParams["COLOR_TYPE"],
				"WIDTH" => $arParams["WIDTH"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"RESTART" => $arParams["RESTART"],
			);

			if(is_array($arCloudParams["arrFILTER"]))
			{
				foreach($arCloudParams["arrFILTER"] as $strFILTER)
				{
					if($strFILTER=="main")
					{
						$arCloudParams["arrFILTER_main"] = $arParams["arrFILTER_main"];
					}
					elseif($strFILTER=="forum" && IsModuleInstalled("forum"))
					{
						$arCloudParams["arrFILTER_forum"] = $arParams["arrFILTER_forum"];
					}
					elseif(mb_strpos($strFILTER,"iblock_")===0)
					{
						foreach($arParams["arrFILTER_".$strFILTER] as $strIBlock)
							$arCloudParams["arrFILTER_".$strFILTER] = $arParams["arrFILTER_".$strFILTER];
					}
					elseif($strFILTER=="blog")
					{
						$arCloudParams["arrFILTER_blog"] = $arParams["arrFILTER_blog"];
					}
					elseif($strFILTER=="socialnetwork")
					{
						$arCloudParams["arrFILTER_socialnetwork"] = $arParams["arrFILTER_socialnetwork"];
					}
				}
			}
			$APPLICATION->IncludeComponent("bitrix:search.tags.cloud", ".default", $arCloudParams, $component, array("HIDE_ICONS" => "Y"));
		}
		?>
		<div class="row gy-4">
			<div class="search-widget widget-item">
				<form action="" method="get">
					<input type="hidden" name="tags" value="<?echo $arResult["REQUEST"]["TAGS"]?>" />
					<input type="hidden" name="how" value="<?echo $arResult["REQUEST"]["HOW"]=="d"? "d": "r"?>" />
					<?if($arParams["USE_SUGGEST"] === "Y"):
						if(mb_strlen($arResult["REQUEST"]["~QUERY"]) && is_object($arResult["NAV_RESULT"]))
						{
							$arResult["FILTER_MD5"] = $arResult["NAV_RESULT"]->GetFilterMD5();
							$obSearchSuggest = new CSearchSuggest($arResult["FILTER_MD5"], $arResult["REQUEST"]["~QUERY"]);
							$obSearchSuggest->SetResultCount($arResult["NAV_RESULT"]->NavRecordCount);
						}
						?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:search.suggest.input",
							"",
							array(
								"NAME" => "q",
								"VALUE" => $arResult["REQUEST"]["~QUERY"],
								"INPUT_SIZE" => -1,
								"DROPDOWN_SIZE" => 10,
								"FILTER_MD5" => $arResult["FILTER_MD5"],
							),
							$component, array("HIDE_ICONS" => "Y")
						);?>
					<?else:?>
						<input type="text" name="q" value="<?=$arResult["REQUEST"]["QUERY"]?>" />
					<?endif;?>
					<button title="Search" type="submit" value="<?echo GetMessage("CT_BSP_GO")?>"><i class="bi bi-search"></i></button>
					<?if($arParams["SHOW_WHERE"] || $arParams["SHOW_WHEN"]):?>
							<script>
							function switch_search_params()
							{
								var sp = document.getElementById('search_params');
								if(sp.style.display == 'none')
								{
									disable_search_input(sp, false);
									sp.style.display = 'block'
								}
								else
								{
									disable_search_input(sp, true);
									sp.style.display = 'none';
								}
								return false;
							}

							function disable_search_input(obj, flag)
							{
								var n = obj.childNodes.length;
								for(var j=0; j<n; j++)
								{
									var child = obj.childNodes[j];
									if(child.type)
									{
										switch(child.type.toLowerCase())
										{
											case 'select-one':
											case 'file':
											case 'text':
											case 'textarea':
											case 'hidden':
											case 'radio':
											case 'checkbox':
											case 'select-multiple':
												child.disabled = flag;
												break;
											default:
												break;
										}
									}
									disable_search_input(child, flag);
								}
							}
							</script>
						<?else:?>
						<?endif;//if($arParams["SHOW_WHERE"] || $arParams["SHOW_WHEN"])?>
					</noindex>
				</form>
			</div>
		</div>

	<div class="row gy-4">
		<?if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false):?>
		<?elseif($arResult["ERROR_CODE"]!=0):?>
			<p><?=GetMessage("CT_BSP_ERROR")?></p>
			<?ShowError($arResult["ERROR_TEXT"]);?>
			<p><?=GetMessage("CT_BSP_CORRECT_AND_CONTINUE")?></p>
			<br /><br />
			<p><?=GetMessage("CT_BSP_SINTAX")?><br /><b><?=GetMessage("CT_BSP_LOGIC")?></b></p>
			<table border="0" cellpadding="5">
				<tr>
					<td align="center" valign="top"><?=GetMessage("CT_BSP_OPERATOR")?></td><td valign="top"><?=GetMessage("CT_BSP_SYNONIM")?></td>
					<td><?=GetMessage("CT_BSP_DESCRIPTION")?></td>
				</tr>
				<tr>
					<td align="center" valign="top"><?=GetMessage("CT_BSP_AND")?></td><td valign="top">and, &amp;, +</td>
					<td><?=GetMessage("CT_BSP_AND_ALT")?></td>
				</tr>
				<tr>
					<td align="center" valign="top"><?=GetMessage("CT_BSP_OR")?></td><td valign="top">or, |</td>
					<td><?=GetMessage("CT_BSP_OR_ALT")?></td>
				</tr>
				<tr>
					<td align="center" valign="top"><?=GetMessage("CT_BSP_NOT")?></td><td valign="top">not, ~</td>
					<td><?=GetMessage("CT_BSP_NOT_ALT")?></td>
				</tr>
				<tr>
					<td align="center" valign="top">( )</td>
					<td valign="top">&nbsp;</td>
					<td><?=GetMessage("CT_BSP_BRACKETS_ALT")?></td>
				</tr>
			</table>
		<?elseif(count($arResult["SEARCH"])>0):?>
			<?if($arParams["DISPLAY_TOP_PAGER"] != "N") echo $arResult["NAV_STRING"]?>
			<?foreach($arResult["SEARCH"] as $arItem):?>
				<div class="col-lg-4">
					<article>
						<div class="card-body">
							<h5 class="card-title"><a href="<?echo $arItem["URL"]?>"><?echo $arItem["TITLE_FORMATED"]?></a></h5>
							<h6 class="card-subtitle mb-2 text-muted"><?echo str_replace('/','.',$arItem["DATE_CHANGE"])?></h6>
							<p class="card-text"><?echo $arItem["BODY_FORMATED"]?></p>
							<?if(
								($arParams["SHOW_ITEM_DATE_CHANGE"] != "N")
								|| ($arParams["SHOW_ITEM_PATH"] == "Y" && $arItem["CHAIN_PATH"])
								|| ($arParams["SHOW_ITEM_TAGS"] != "N" && !empty($arItem["TAGS"]))
							):?>
							<div class="search-item-meta">
								<?if (
									$arParams["SHOW_RATING"] == "Y"
									&& $arItem["RATING_TYPE_ID"] <> ''
									&& $arItem["RATING_ENTITY_ID"] > 0
								):?>
								<div class="search-item-rate">
								<?
								$APPLICATION->IncludeComponent(
									"bitrix:rating.vote", $arParams["RATING_TYPE"],
									Array(
										"ENTITY_TYPE_ID" => $arItem["RATING_TYPE_ID"],
										"ENTITY_ID" => $arItem["RATING_ENTITY_ID"],
										"OWNER_ID" => $arItem["USER_ID"],
										"USER_VOTE" => $arItem["RATING_USER_VOTE_VALUE"],
										"USER_HAS_VOTED" => $arItem["RATING_USER_VOTE_VALUE"] == 0? 'N': 'Y',
										"TOTAL_VOTES" => $arItem["RATING_TOTAL_VOTES"],
										"TOTAL_POSITIVE_VOTES" => $arItem["RATING_TOTAL_POSITIVE_VOTES"],
										"TOTAL_NEGATIVE_VOTES" => $arItem["RATING_TOTAL_NEGATIVE_VOTES"],
										"TOTAL_VALUE" => $arItem["RATING_TOTAL_VALUE"],
										"PATH_TO_USER_PROFILE" => $arParams["~PATH_TO_USER_PROFILE"],
									),
									$component,
									array("HIDE_ICONS" => "Y")
								);?>
								</div>
								<?endif;?>
								<?if($arParams["SHOW_ITEM_TAGS"] != "N" && !empty($arItem["TAGS"])):?>
									<div class="search-item-tags"><label><?echo GetMessage("CT_BSP_ITEM_TAGS")?>: </label><?
									foreach ($arItem["TAGS"] as $tags):
										?><a href="<?=$tags["URL"]?>"><?=$tags["TAG_NAME"]?></a> <?
									endforeach;
									?></div>
								<?endif;?>

								<?if($arParams["SHOW_ITEM_DATE_CHANGE"] != "N"):?>
									<div class="search-item-date"><label><?echo GetMessage("CT_BSP_DATE_CHANGE")?>: </label><span><?echo $arItem["DATE_CHANGE"]?></span></div>
								<?endif;?>
							</div>
						</div>
						<?endif?>
					</article>
				</div>
			<?endforeach;?>
			<?if($arParams["DISPLAY_BOTTOM_PAGER"] != "N") echo $arResult["NAV_STRING"]?>
			<?if($arParams["SHOW_ORDER_BY"] != "N"):?>
				<div class="search-sorting"><label><?echo GetMessage("CT_BSP_ORDER")?>:</label>&nbsp;
				<?if($arResult["REQUEST"]["HOW"]=="d"):?>
					<a href="<?=$arResult["URL"]?>&amp;how=r"><?=GetMessage("CT_BSP_ORDER_BY_RANK")?></a>&nbsp;<b><?=GetMessage("CT_BSP_ORDER_BY_DATE")?></b>
				<?else:?>
					<b><?=GetMessage("CT_BSP_ORDER_BY_RANK")?></b>&nbsp;<a href="<?=$arResult["URL"]?>&amp;how=d"><?=GetMessage("CT_BSP_ORDER_BY_DATE")?></a>
				<?endif;?>
				</div>
			<?endif;?>
		<?else:?>
			<?ShowNote(GetMessage("CT_BSP_NOTHING_TO_FOUND"));?>
		<?endif;?>
	</div>
</section>