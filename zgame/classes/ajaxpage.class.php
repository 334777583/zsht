<?php 
/**
 * FileName: AjaxPage.class.php
 * Description: ajax实现的分页
 * Author: xiaochengcheng
 * Date: 2013-3-14 13:19:25
 * Version: 1.00
 **/
class AjaxPage{
	protected $pageSize='10';//设置每页显示多少条记录
	protected $curPage = 1;//当前页
	protected $off;//设置limit偏量
	protected $recordNum = 0;//总记录数
	protected $totalPage = 1;//总页面数
	protected $functionName = "";//翻页调用的ajax函数名
	protected $goFunctionName = "go";//直接跳转调用函数
	protected $pageId = "page";//页数输入框id（用于获取页面数）
	protected $firstPage = "";
	protected $lastPage = "";
	protected $prePage = "";
	protected $nextPage = "";
	protected $pageHtml = "";
	
	public function __construct($pageSize,$curPage,$recordNum,$functionName,$goFunctionName = "go",$pageId = "page") {
        $this->pageSize = $pageSize;
		$this->curPage = $curPage;
		$this->recordNum = $recordNum;
		$this->functionName = $functionName;
		$this->goFunctionName = $goFunctionName;
		$this->pageId = $pageId;
		$this->off = ($this->curPage-1)*$this->pageSize;
		$this->totalPage = ceil($this->recordNum/$this->pageSize);
    }
   
    public function getFirstPage(){
    	$this->firstPage = "onclick='".$this->functionName."(1)'";
    	return $this->firstPage;
    }

	public function getLastPage(){
		$this->lastPage = "onclick='".$this->functionName."(".$this->totalPage.")'";
		return $this->lastPage;
	}
	
	public function getPrePage(){
		if($this->curPage > 1){
			$this->prePage = "onclick='".$this->functionName."(".intval($this->curPage-1).")'";
		}else{
			$this->prePage = "onclick='".$this->functionName."(".$this->curPage.")'";
		}
		
		return $this->prePage;
	}
	
	public function getNextPage(){
		if($this->curPage <$this->totalPage){
			$this->nextPage = "onclick='".$this->functionName."(".intval($this->curPage+1).")'";
		}else{
			$this->nextPage = "onclick='".$this->functionName."(".$this->curPage.")'";
		}
		
		return $this->nextPage;
	}
	
	public function  getOff(){
		$this->off = ($this->curPage-1)*$this->pageSize;
		return $this->off;
	}
	
	public function getPageHtml(){
		$this->pageHtml .= "<div class=\"pages\">".
						"<a href=\"javascript:void(0)\"".$this->getFirstPage().">首页</a>&nbsp;&nbsp;".
						"<a href=\"javascript:void(0)\"".$this->getPrePage().">上一页</a>&nbsp;&nbsp;".
						"<a href=\"javascript:void(0)\"".$this->getNextPage().">下一页</a>&nbsp;&nbsp;".
						"<a href=\"javascript:void(0)\"".$this->getLastPage().">尾页</a>&nbsp;&nbsp;".
						"第".$this->curPage."/".$this->totalPage."页&nbsp;&nbsp;转到".
						"<input type=\"text\" id=\"$this->pageId\" value=\"$this->curPage\" size=\"3\" class=\"text\">".
						"<a href=\"javascript:void(0);\" class=\"go\" onclick=\"".$this->goFunctionName."()\"></a>页".
					"</div>";
		return $this->pageHtml;
	}
}
