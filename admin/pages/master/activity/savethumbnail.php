<?php
session_start();
if( file_exists( '../../lib/var.inc.php' ) ) require_once( '../../lib/var.inc.php' );
if( file_exists( BEANPATH . '/PostMasterBean.php' ) ) require_once( BEANPATH . '/PostMasterBean.php' );
if( file_exists( DAOPATH . '/PostMasterDAO.php' ) ) require_once( DAOPATH . '/PostMasterDAO.php' );

$bean = new UserMasterBean();
$bean->doAction();
