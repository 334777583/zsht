<?php
class autoipsearchdat{

    private $hFile;
    private $FileName = 'qqwry.dat';
    private $iFirstIpOffset;
    private $iLastIpOffset;
    private $iTotalIps;
    private $sOutEncoding;
    private $sUnknown;
    private $iIp;
    private $iBeginIp;
    private $iEndIp;
    private $sCountry;
    private $sArea;
 
    function __construct($iOutEncoding = 'utf-8', $sUnknown = NULL){
        $this->sOutEncoding = $iOutEncoding;
		if($sUnknown){
			$this->sUnknown = $sUnknown;
		}else{
			$this->sUnknown = 'unknown';
		}
		$d = dirname(__FILE__).'/'.$this->FileName;
		if(! is_readable($d)){
			die('File not find');
		}
        if(false !== ($this->hFile = @fopen($d, 'rb'))){
            $this->iFirstIpOffset = $this->readLong();
            $this->iLastIpOffset = $this->readLong();
            $this->iTotalIps = ($this->iLastIpOffset - $this->iFirstIpOffset) / 7;
        }
    }
 
    public function __destruct(){
        unset($this->sCountry);
        unset($this->sArea);
        @fclose($this->hFile);
        $this->hFile = null;
    }
 
    private function convertEncoding($sContent){
        if(!empty($this->sOutEncoding) && 'gbk' !== $this->sOutEncoding){
            if(function_exists('mb_convert_encoding')){
                return mb_convert_encoding($sContent, $this->sOutEncoding, 'gbk');
            }
            else{
                return iconv('gbk', $this->sOutEncoding, $sContent);
            }
        }
        return $sContent;
    }
 
    private function clean($sContent){
        return trim(str_replace(
            array('CZ88.NET'),
            $this->sUnknown,
            $this->convertEncoding($sContent)
        ));
    }
 
    private function readLong(){
        $aResult = unpack('V', fread($this->hFile, 4));
        return $aResult[1];
    }
 
    private function readUInt32(){
        $aResult = unpack('V', fread($this->hFile, 4));
        return bindec(decbin($aResult[1]));
    }
 
    private function read3ByteLong(){
        $result = unpack('V', fread($this->hFile, 3) . chr(0));
        return $result[1];
    }
 
    private function readString($sStr = ''){
        $sChar = fread($this->hFile, 1);
        while(ord($sChar) > 0){
            $sStr .= $sChar;
            $sChar = fread($this->hFile, 1);
        }
        return $sStr;
    }
 
    private function readArea(){
        $iByte = fread($this->hFile, 1);
        switch(ord($iByte)){
            case 0:
                return '';
            case 1:
            case 2:
                fseek($this->hFile, $this->read3ByteLong());
                return $this->readString();
            default:
                return $this->readString($iByte);
        }
    }
 
    private function locateIp(){
        $iFound = $this->iLastIpOffset;
        $iIp = pack('N', $this->iIp);
        $iIpCount = $this->iTotalIps;
        $iIterator = 0;
        while($iIterator <= $iIpCount){
            $iHalf = floor(($iIterator + $iIpCount) / 2);
            $iOffset = $this->iFirstIpOffset + $iHalf * 7;
            fseek($this->hFile, $iOffset);
            $iBeginIp = strrev(fread($this->hFile, 4));
            if($iIp < $iBeginIp){
                $iIpCount = $iHalf - 1;
            }
            else{
                fseek($this->hFile, $this->read3ByteLong());
                $iEndIp = strrev(fread($this->hFile, 4));
                if($iIp > $iEndIp){
                    $iIterator = $iHalf + 1;
                }
                else{
                    $iFound = $iOffset;
                    break;
                }
            }
        }
 
        fseek($this->hFile, $iFound);
        $this->iBeginIp = $this->readLong();
        $iIpRecordOffset = $this->read3ByteLong();
        fseek($this->hFile, $iIpRecordOffset);
        $this->iEndIp = $this->readLong();
        return $iIpRecordOffset;
    }
 
    private function readDetail($iIpRecordOffset){
        $iFlag = fread($this->hFile, 1);
        switch(ord($iFlag)){
            case 1:
                $iCountryOffset = $this->read3ByteLong();
                fseek($this->hFile, $iCountryOffset);
                $iFlag = fread($this->hFile, 1);
                switch(ord($iFlag)){
                    case 2:
                        fseek($this->hFile, $this->read3ByteLong());
                        $this->sCountry = $this->readString();
                        fseek($this->hFile, $iCountryOffset + 4);
                        $this->sArea = $this->readArea();
                        break;
                    default:
                        $this->sCountry = $this->readString($iFlag);
                        $this->sArea = $this->readArea();
                        break;
                }
                break;
            case 2:
                fseek($this->hFile, $this->read3ByteLong());
                $this->sCountry = $this->readString();
                fseek($this->hFile, $iIpRecordOffset + 8);
                $this->sArea = $this->readArea();
                break;
            default:
                $this->sCountry = $this->readString($iFlag);
                $this->sArea = $this->readArea();
                break;
        }
 
        $this->sCountry = $this->clean($this->sCountry);
        $this->sArea = $this->clean($this->sArea);
    }
 
    private function returnFullArea(){
        return array(
            'ip' => long2ip($this->iIp),
            'begin' => long2ip($this->iBeginIp),
            'end' => long2ip($this->iEndIp),
            'country' => $this->sCountry,
            'area' => $this->sArea
        );
    }
 
    function findIp($sIp,$b=false){
        $this->iIp = intval(ip2long($sIp));
        $this->readDetail($this->locateIp());
        if($b){
            return $this->returnFullArea();
        }
        else{
            return $this->sCountry; //. ',' . $this->sArea;
        }
    }
 
    public function findDomain($sDomain, $iReturnType = 0){
        return $this->findIp(gethostbyname($sDomain), $iReturnType);
    }
}

// $o = new IpSearchDat();
// echo $o->findIp('218.19.227.180');

?>