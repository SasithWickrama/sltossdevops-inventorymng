<?php
namespace App\Enums;

use BenSampo\Enum\Enum;

final class CommentTypes extends Enum
{
    const  WGCHANGE = 'WGCHANGE';
    const  STATUSCHANGE = 'STATUSCHANGE';
    const  USERCOM = 'USERCOM';
    
    const  ITEMREQ = 'ITEMREQ';
    const  ITEMREQUPD = 'ITEMREQUPD';
    const  ITEMREQDEL = 'ITEMREQDEL';

    const  ITEMRES = 'ITEMRES';
    const  ITEMRESUPD = 'ITEMRESUPD';
    const  ITEMRESDEL = 'ITEMRESDEL';

    const  ITEMUPD = 'ITEMUPD';

    const DEPOTCRE = 'DEPOTCRE';
    const DEPOTUPD = 'DEPOTUPD';
    const DEPOTSYNC = 'DEPOTSYNC';
    const DEPOTSYNCSTATUS = 'DEPOTSYNCSTATUS';
    const DEPOTUSERCOM = 'DEPOTUSERCOM';
    
}


