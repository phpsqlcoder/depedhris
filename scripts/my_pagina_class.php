<?php

/************************************************************************
192.168.7.199
255.255.255.0
7.198
MyPagina ver. 1.04
Use this class to handle MySQL record sets and get page navigation links 

Copyright (c) 2005 - 2008, Olaf Lederer
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the finalwebsites.com nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

_________________________________________________________________________
available at http://www.finalwebsites.com/snippets.php?id=29
Comments & suggestions: http://www.finalwebsites.com/forums/forum/php-classes-support-forum

*************************************************************************

Updates & bugfixes

ver. 1.01 - There was a small bug inside the page_info() method while showing
the last page (set). The error (last record) is fixed. There is also a small 
update in the method set_page(), the check is now with $_REQUEST values in 
place of $_GET values.

ver. 1.02 - The link text (and the new image function) for the forward and backward links will be created with the new method build_back_or_forward(). Because there is no need anymore the variables str_forward and str_backward are removed. Check the example file for the possibility to  use images in place of strings for the back- and forward navigation and the modified navigation() method.

ver 1.03 - There is a new variable $max_rows, this variable is used to limit the maximum of results during a query. That will say if there are more rows then the number of $max_rows only the last one will show up. Use the new variable $outstanding_rows if you need to inform the user about that. Leave the value inside the constructor empty to disaple the function. Several variables are removed to give this class a safer and better structure. The method page_info() that builds the string with page number information is changed, only one (input) string (formatted with sprintf) is needed now. 

ver. 1.04 - Inside the example is a new navigation the showing only number in some collored boxes, these are added as an alternative type for a page navigation. There is also a new example for back- and forward links using text only. I fixed also some small bugs, one in the navigation() method, the start number for the page links was in some cases to high (if max links and number of link was the same) and one in the method get_page_result(). In this version there are also links for the first and the last page available under the conddition that the same link is not available with the other links (numbers). For this function there are new variables used in the class file and the configuration file. the methods navigation() and build_back_or_forward() are updated. The new function is disabled by default, check the updated example about how to use the feature.

*************************************************************************/

define("QS_VAR", "page"); // the variable name inside the query string (don't use this name inside other links)
define("NUM_ROWS", 100); // the number of records on each page

define("STR_FWD", "&gt;&gt;"); // the string is used for a link (step forward)
define("STR_BWD", "&lt;&lt;"); // the string is used for a link (step backward)

define("STR_START", "First"); // the string is used for a link (first step)
define("STR_END", "Last"); // the string is used for a link (last step)

// use the right pathes to get it working with the php function getimagesize
define("IMG_FWD", "images/pagination/forward.gif"); // the image for forward link 
define("IMG_BWD", "images/pagination/backward.gif"); // the image for backward link 
define("IMG_START", "images/pagination/start.gif"); // the image for the first link 
define("IMG_END", "images/pagination/end.gif"); // the image for the last link 

define("NUM_LINKS", 1); // the number of links inside the navigation (the default value)

// you need these constant vars too, include them with your "global" database connection file or define them below
if (!defined("DB_SERVER")) define("DB_SERVER", "localhost"); // In most of the times it's localhost
if (!defined("DB_USER")) define("DB_USER", "root"); // the name of the database user
if (!defined("DB_PASSWORD")) define("DB_PASSWORD", "pangitka"); // the password for the database user
if (!defined("DB_NAME")) define("DB_NAME", "hris"); // the name of the database

class MyPagina {
	
	var $sql;
	var $result;
	var $outstanding_rows = false;
	var $hashtag = '';
	
	var $get_var = QS_VAR;
	
	var $forw = STR_FWD;
	var $forw_img = IMG_FWD;
	var $back = STR_BWD;
	var $back_img = IMG_BWD;
	
	// new in ver. 1.04
	var $start = STR_START;
	var $start_img = IMG_START;
	var $end = STR_END;
	var $end_img = IMG_END;

	var $max_rows;
	var $number_links = NUM_LINKS;
	var $rows_on_page = NUM_ROWS;	
	
	
	// constructor
	function MyPagina($rows = 0, $connect = true) {
		if ($connect) $this->connect_db();
		$this->max_rows = $rows;

	}
	// sets the current page number
	function set_page() {
		$page = (!empty($_REQUEST[$this->get_var])) ? (int)$_REQUEST[$this->get_var] : 0;
		return $page;
	}
	// gets the total number of records 
	function get_total_rows() {
		$tmp_result = mysql_query($this->sql);
		$all_rows = mysql_num_rows($tmp_result);
		if (!empty($this->max_rows) && $all_rows > $this->max_rows) {
			$all_rows = $this->max_rows;
			$this->outstanding_rows = true;
		}
		mysql_free_result($tmp_result);
		return $all_rows;
	}
	// database connection
	function connect_db() {
		$connId = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
		mysql_select_db(DB_NAME, $connId);
	}
	// get the totale number of result pages
	function get_num_pages() {
		$number_pages = ceil($this->get_total_rows() / $this->rows_on_page);
		return $number_pages;
	}
	// returns the records for the current page
	function get_page_result() {
		$start = $this->set_page() * $this->rows_on_page;
		$diff = $this->get_total_rows() - $start;
		$end = ($diff < $this->rows_on_page) ? $diff : $this->rows_on_page;
		$page_sql = sprintf("%s LIMIT %s, %s", $this->sql, $start, $end);
		$this->result = mysql_query($page_sql);
		return $this->result;
	}
	// get the number of rows on the current page
	function get_page_num_rows() {
		$num_rows = mysql_num_rows($this->result);
		return $num_rows;
	}
	// free the database result
	function free_page_result() {
		mysql_free_result($this->result);
	}
	// function to handle other querystring than the page variable
	function rebuild_qs($curr_var) {
		$qs = '';
		if (!empty($_SERVER['QUERY_STRING'])) {
			$parts = explode("&", $_SERVER['QUERY_STRING']);
			$newParts = array();
			foreach ($parts as $val) {
				if (stristr($val, $curr_var) == false)  {
					array_push($newParts, $val);
				}
			}
			if (count($newParts) != 0) {
				$qs = "&".implode("&", $newParts); // this is your new created query string
			}  
		}
		if ($this->hashtag != '') $qs .= $this->hashtag;
		return $qs; 
	} 
	// this method will return the navigation links for the conplete recordset
	function navigation($separator = " | ", $css_current = "", $numbers_only = false, $only_back_forward = false, $use_images = false, $use_start_end = false) {
		$max_links = $this->number_links;
		$curr_pages = $this->set_page();
		$all_pages = $this->get_num_pages() - 1;
		if (!$only_back_forward) {
			$max_links = ($max_links < 2) ? 2 : $max_links;
		}
		if ($curr_pages <= $all_pages && $curr_pages >= 0) {
			if ($curr_pages > ceil($max_links/2)) {
				$start = ($curr_pages - ceil($max_links/2) > 0) ? $curr_pages - ceil($max_links/2) : 1;
				$end = $curr_pages + ceil($max_links/2);
				if ($end >= $all_pages) {
					$end = $all_pages + 1;
					$start = ($all_pages - ($max_links - 1) > 0) ? $all_pages  - ($max_links - 1) : 0;
				}
			} else {
				$start = 0;
				$end = ($all_pages >= $max_links) ? $max_links : $all_pages + 1;
			}
			if ($all_pages >= 1) {
				$forward = $curr_pages + 1;
				$backward = $curr_pages - 1;
				// the text two labels are new sinds ver 1.02
				$lbl_forward = $this->build_back_or_forward("forward", $use_images);
				$lbl_backward = $this->build_back_or_forward("backward", $use_images);
				$navi_string = "";
				$middle_part = "";
					
				if (!$only_back_forward) {
					for($a = $start + 1; $a <= $end; $a++){
						$theNext = $a - 1; // because a array start with 0
						if ($theNext != $curr_pages) {
							$middle_part .= "<a rel=\"nofollow\" href=\"".$_SERVER['PHP_SELF']."?".$this->get_var."=".$theNext.$this->rebuild_qs($this->get_var)."\">";
							$middle_part .= $a."</a>";
							$middle_part .= ($theNext < ($end - 1)) ? $separator : "";
						} else {
							$middle_part .= ($css_current != "") ? "<span class=\"".$css_current."\">".$a."</span>" : $a;
							$middle_part .= ($theNext < ($end - 1)) ? $separator : "";
						}
					}
				}
				if (!$numbers_only) {
					// ver. 1.04 add extra links (start/end)
					$lbl_start = $this->build_back_or_forward("start", $use_images);
					$lbl_end = $this->build_back_or_forward("end", $use_images);
					if ($curr_pages > 0) {
						if ($use_start_end && ($curr_pages > ($max_links-2))) {
							// add here the start link
							$navi_string .=  "<a rel=\"nofollow\" href=\"".$_SERVER['PHP_SELF']."?".$this->get_var."=0".$this->rebuild_qs($this->get_var)."\">".$lbl_start."</a>&nbsp;";
						}
						$navi_string .=  "<a rel=\"nofollow\" href=\"".$_SERVER['PHP_SELF']."?".$this->get_var."=".$backward.$this->rebuild_qs($this->get_var)."\">".$lbl_backward."</a>&nbsp;";
					} else {
						$navi_string .=  $lbl_backward."&nbsp;";
					}
					$navi_string .= $middle_part; // the number links
					if ($curr_pages < $all_pages) {
						$navi_string .=  "&nbsp;<a rel=\"nofollow\" href=\"".$_SERVER['PHP_SELF']."?".$this->get_var."=".$forward.$this->rebuild_qs($this->get_var)."\">".$lbl_forward."</a>";
						if ($use_start_end && ($curr_pages < ($all_pages-$max_links+2))) {
							// add here the end links
							$navi_string .=  "&nbsp;<a rel=\"nofollow\" href=\"".$_SERVER['PHP_SELF']."?".$this->get_var."=".$all_pages.$this->rebuild_qs($this->get_var)."\">".$lbl_end."</a>";
						}
					} else {
						$navi_string .= "&nbsp;".$lbl_forward;
					}
				} else {
					$navi_string .= $middle_part; // the number links
				}
				return $navi_string;
			}
		}
	}
	// function to create the back/forward elements; $what = forward or backward
	// type = text or img
	// ver. 1.04, added extra labels for the start and end links
	function build_back_or_forward($what, $img = false) {
		$label['text']['forward'] = $this->forw;
		$label['text']['backward'] = $this->back;
		$label['img']['forward'] = $this->forw_img;
		$label['img']['backward'] = $this->back_img;
		$label['text']['start'] = $this->start;
		$label['text']['end'] = $this->end;
		$label['img']['start'] = $this->start_img;
		$label['img']['end'] = $this->end_img;
		if ($img) {
			$img_info = getimagesize($label['img'][$what]);
			$label = "<img src=\"".$label['img'][$what]."\" ".$img_info[3]." border=\"0\">";
		} else {
			$label = $label['text'][$what];
		}
		return $label;
	}
	// this info will tell the visitor which number of records are shown on the current page
	function page_info($str = "Result: %d - %d of %d") {
		$first_rec_no = ($this->set_page() * $this->rows_on_page) + 1;
		$last_rec_no = $first_rec_no + $this->rows_on_page - 1;
		$last_rec_no = ($last_rec_no > $this->get_total_rows()) ? $this->get_total_rows() : $last_rec_no;
		$info = sprintf($str, $first_rec_no, $last_rec_no, $this->get_total_rows());
		return $info;
	}
	// simple method to show only the page back and forward link.
	function back_forward_link($images = false) {

		$simple_links = $this->navigation(" ", "", false, true, $images);
		return $simple_links;
	}
}
?>
