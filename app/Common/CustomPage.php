<?php
namespace App\Common;


/**
 * 自定义分页类,主要用于产生分页视图
 * @package App\Library
 */
class CustomPage
{
    /**
     * @param $text
     * @return string
     */
//     public static function getActivePageWrapper($text)
//     {
//         return '<li><span>' . $text . '</span></li>';
//     }


    /**
     * 获取当前页按钮的页面样式
     * @param $url
     * @param $page
     * @return string
     */
    public static function getActivePageLinkWrapper($url, $page)
    {
        return '<li class="paginate_button active" aria-controls="DataTables_Table_0" tabindex="0"><a href="javascript:void(0);">' . $page . '</a></li>';
    }


    /**
     * 获取非当前页按钮的页面样式
     * @param $url
     * @param $page
     * @return string
     */
    public static function getPageLinkWrapper($url, $page)
    {
        return '<li class="paginate_button" aria-controls="DataTables_Table_0" tabindex="0"><a href="' . $url . '">' . $page . '</a></li>';
    }

    /**
     * 获取跳转到指定页按钮的页面样式
     * @param $url
     * @param $page
     * @param $totalPage
     * @return string
     */
    public static function getJumpPageLinkWrapper($url, $page, $totalPage){
        $pageGoPre = '<ul class="pagination-jump">';
        $pageGoEnd = '</ul>';

        $pageGoTemp = '<li class=""><span>共 '.$totalPage.' 页，跳转到 </span></li>';
        $pageGoTemp .= '<li class=""><input type="text" class="page-input" value=""/></li>';
        $pageGoTemp .= '<li class="page-go"><a class="jump-go" data-totalpage="'.$totalPage.'" data-url="'.$url.'">GO</a></li>';

        $pageView = $pageGoPre . $pageGoTemp . $pageGoEnd;
        return $pageView;
    }

    /**
     * 获取整个的分页样式
     * @param $nowPage 当前页
     * @param $totalPage 共多少页面
     * @param $baseUrl  当前url
     * @param $search   搜索
     * @return string
     */
    public static function getSelfPageView($nowPage, $totalPage, $baseUrl, $search)
    {

        $pagePre = '<ul class="pagination">';
        $pageEnd = '</ul>';

        $pageLastStr = '';
        $pageNextStr = '';
        $pageHeadStr = '';
        $pageTailStr = '';
        if ($nowPage <= 1) {
            $nowPage = 1;
            $pageLastStr = '<li class="paginate_button previous disabled" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_previous"><a href="javascript:void(0);">上一页</a></li>';
        }
        if ($nowPage >= $totalPage) {
            $nowPage = $totalPage;
            $pageNextStr = '<li class="paginate_button next disabled" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_next"><a href="javascript:void(0);">下一页</a></li>';
        }

        $search['totalPage'] = $totalPage;

        if (empty($pageLastStr)) {
            $search['page'] = 1;
            $headSearchStr = self::arrayToSearchStr($search);
            $url = url($baseUrl) . '?' . $headSearchStr;
            $pageHeadStr = '<li class="paginate_button next" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_next"><a href="'.$url.'">首页</a></li>';

            $lastPage = $nowPage - 1;
            $search['page'] = $lastPage;
            $lastSearchStr = self::arrayToSearchStr($search);
            $url = url($baseUrl) . '?' . $lastSearchStr;
            $pageLastStr = '<li class="paginate_button previous" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_previous"><a href="'.$url.'">上一页</a></li>';//self::getPageLinkWrapper($url, '上一页');
        }


        if (empty($pageNextStr)) {
            $pageNext = $nowPage + 1;
            $search['page'] = $pageNext;
            $lastSearchStr = self::arrayToSearchStr($search);
            $url = url($baseUrl) . '?' . $lastSearchStr;
            $pageNextStr = '<li class="paginate_button next" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_next"><a href="'.$url.'">下一页</a></li>';//self::getPageLinkWrapper($url, '下一页');

            $search['page'] = $totalPage;
            $tailSearchStr = self::arrayToSearchStr($search);
            $url = url($baseUrl) . '?' . $tailSearchStr;
            $pageTailStr = '<li class="paginate_button next" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_next"><a href="'.$url.'">尾页</a></li>';
        }

        $search['page'] = $nowPage;
        $goSearchStr = self::arrayToSearchStr($search);
        $url = url($baseUrl) . '?' . $goSearchStr;
        $pageJumpStr = self::getJumpPageLinkWrapper($url, $nowPage, $totalPage);

        $pageTemp = '';
        $pageRange = self::getPageRange($nowPage, $totalPage);
        $pageTemp .= $pageHeadStr.$pageLastStr;
        foreach ($pageRange as $page) {
            $search['page'] = $page;
            $searchStr = self::arrayToSearchStr($search);
            $url = url($baseUrl) . '?' . $searchStr;
            if ($page == $nowPage) {
                $pageTemp .= self::getActivePageLinkWrapper($url, $page);
            } else {
                $pageTemp .= self::getPageLinkWrapper($url, $page);
            }
        }
        $pageTemp .= $pageNextStr.$pageTailStr.$pageJumpStr;
        $pageView = $pagePre . $pageTemp . $pageEnd;
        return $pageView;
    }


    /**
     * 获取实际显示页面范围的范围
     * @param $nowPage
     * @param $totalPage
     * @return array
     */
    public static function getPageRange($nowPage, $totalPage)
    {
        $returnArray = [];

        if ($totalPage <= 5) {
            for ($i = 1; $i <= $totalPage; $i++) {
                $returnArray[] = $i;
            }
        } else {
            $lengthLeft = $nowPage - 1;
            $lengthRight = $totalPage - $nowPage;

            if (($lengthLeft < 2) && ($lengthRight < 2)) {
                $returnArray = [];
            } elseif (($lengthLeft < 2) && ($lengthRight > 2)) {
                for ($i = 1; $i <= 5; $i++) {
                    $returnArray[] = $i;
                }
            } elseif (($lengthLeft > 2) && ($lengthRight < 2)) {
                $start = $totalPage - 4;
                for ($i = $start; $i <= $totalPage; $i++) {
                    $returnArray[] = $i;
                }
            } else {
                for ($i = $nowPage - 2; $i <= $nowPage + 2; $i++) {
                    $returnArray[] = $i;
                }
            }
        }

        return $returnArray;
    }


    /**
     * 将搜索的数组拼接成为url
     * 注意：PHP的内置函数http_build_query，会自动将没有值的参数清除，导致blade模板报错
     * @param $array
     * @return string
     */
    public static function arrayToSearchStr($array)
    {
        $fields_string = '';

        reset($array);
        end($array);
        $lastKey = key($array);
        reset($array);

        foreach ($array as $key => $value) {
            if ($key != $lastKey) {
                $fields_string .= $key . '=' . $value . '&';
            } else {
                $fields_string .= $key . '=' . $value;
            }
        }
        rtrim($fields_string, '&');

        return $fields_string;
    }


    public static function arrayToObject($e)
    {

        if (gettype($e) != 'array') return;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $e[$k] = (object)self::arrayToObject($v);
        }
        return (object)$e;
    }

    public static function objectToArray($e)
    {
        $e = (array)$e;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'resource') return;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $e[$k] = (array)self::objectToArray($v);
        }
        return $e;
    }
}