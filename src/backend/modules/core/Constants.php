<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-04-19 1:00 PM
 */

namespace backend\modules\core;


class Constants
{
    //menus
    const MENU_ORGANIZATION = 'ORG';
    const SUBMENU_REGISTRATION_DOCUMENTS = 'REG_DOCS';
    //resources
    const RES_MEMBERS = 'ORG_MEMBER';
    const RES_SUPPLIER = 'ORG_SUPPLIER';
    const RES_REGISTRATION_DOCUMENT = 'ORG_REGISTRATION_DOCUMENT';
    //member tab
    const TAB_ALL_MEMBERS = 1;
    const TAB_PHARMACIES = 2;
    const TAB_HOSPITALS = 3;
    const TAB_CLINICS = 4;
    const TAB_PENDING_APPROVAL = 5;
    //supplier tab
    const TAB_ALL_SUPPLIERS = 11;
    const TAB_DISTRIBUTORS = 12;
    const TAB_MANUFACTURERS = 13;

}