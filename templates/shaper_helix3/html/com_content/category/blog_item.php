<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if(!function_exists('cut_string')){
	function cut_string($title, $max)
	{
		if($title!=''){
			if(is_array($title)) list($string, $match_to) = $title;
			else { $string = $title; $match_to = $title{0}; }

			$match_start = stristr($string, $match_to);
			$match_compute = strlen($string) - strlen($match_start);

			if (strlen($string) > $max)
			{
				if ($match_compute < ($max - strlen($match_to)))
				{
					$pre_string = substr($string, 0, $max);
					$pos_end = strrpos($pre_string, " ");
					if($pos_end === false) $string = $pre_string."...";
					else $string = substr($pre_string, 0, $pos_end)."...";
				}
				else if ($match_compute > (strlen($string) - ($max - strlen($match_to))))
				{
					$pre_string = substr($string, (strlen($string) - ($max - strlen($match_to))));
					$pos_start = strpos($pre_string, " ");
					$string = "...".substr($pre_string, $pos_start);
					if($pos_start === false) $string = "...".$pre_string;
					else $string = "...".substr($pre_string, $pos_start);
				}
				else
				{
					$pre_string = substr($string, ($match_compute - round(($max / 3))), $max);
					$pos_start = strpos($pre_string, " "); $pos_end = strrpos($pre_string, " ");
					$string = "...".substr($pre_string, $pos_start, $pos_end)."...";
					if($pos_start === false && $pos_end === false) $string = "...".$pre_string."...";
					else $string = "...".substr($pre_string, $pos_start, $pos_end)."...";
				}

				$match_start = stristr($string, $match_to);
				$match_compute = strlen($string) - strlen($match_start);
			}

			return $string;
		}else{
			return $string ='';
		}
	}
}

// Create a shortcut for params.
$params = $this->item->params;
$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$canEdit 	 = $this->item->params->get('access-edit');
$info    	 = $params->get('info_block_position', 0);
$useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') );

// Post Format
$post_attribs = new JRegistry(json_decode( $this->item->attribs ));
$post_format = $post_attribs->get('post_format', 'standard');
?>

<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate())) : ?>
	<div class="system-unpublished">
<?php endif; ?>

<?php
	if($post_format=='standard') {
		echo JLayoutHelper::render('joomla.content.intro_image', $this->item);
	} else {
		echo JLayoutHelper::render('joomla.content.post_formats.post_' . $post_format, array('params' => $post_attribs, 'item' => $this->item));
	}
?>

<div class="entry-header<?php //echo $tpl_params->get('show_post_format') ? ' has-post-format': ''; ?>">

	<?php echo JLayoutHelper::render('joomla.content.post_formats.icons',  $post_format); ?>

	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
	<?php endif; ?>
	
	<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>
</div>

<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
<?php endif; ?>

<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
<?php endif; ?>
<?php echo $this->item->event->beforeDisplayContent; ?>

<?php $rating = (int) $this->item->rating; ?>

<?php echo cut_string(strip_tags($this->item->introtext), 600); ?>

<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
	<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
<?php  endif; ?>

<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
		$link = new JUri($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif; ?>

	<?php echo JLayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link)); ?>

<?php endif; ?>

<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate())) : ?>
</div>
<?php endif; ?>

<?php if ($params->get('show_tags') && !empty($this->item->tags->itemTags)) : ?>
	<?php echo JLayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?>
