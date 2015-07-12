<?php
class Mage_Core_Block_Profiler extends Mage_Core_Block_Abstract //相同的基类
{
    protected function _toHtml() //自定义了私有方法
    {
        if (!$this->_beforeToHtml()
            || !Mage::getStoreConfig('dev/debug/profiler')
            || !Mage::helper('core')->isDevAllowed()) {
            return '';
        }

        $timers = Varien_Profiler::getTimers();

        #$out = '<div style="position:fixed;bottom:5px;right:5px;opacity:.1;background:white" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.1">';
        #$out = '<div style="opacity:.1" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.1">';
        $out = "<a href=\"javascript:void(0)\" onclick=\"$('profiler_section').style.display=$('profiler_section').style.display==''?'none':''\">[profiler]</a>";
        $out .= '<div id="profiler_section" style="background:white; display:block">';
        $out .= '<pre>Memory usage: real: '.memory_get_usage(true).', emalloc: '.memory_get_usage().'</pre>';
        $out .= '<table border="1" cellspacing="0" cellpadding="2" style="width:auto">';
        $out .= '<tr><th>Code Profiler</th><th>Time</th><th>Cnt</th><th>Emalloc</th><th>RealMem</th></tr>';
        foreach ($timers as $name=>$timer) {
            $sum = Varien_Profiler::fetch($name,'sum');
            $count = Varien_Profiler::fetch($name,'count');
            $realmem = Varien_Profiler::fetch($name,'realmem');
            $emalloc = Varien_Profiler::fetch($name,'emalloc');
            if ($sum<.0010 && $count<10 && $emalloc<10000) {
                continue;
            }
            $out .= '<tr>'
                .'<td align="left">'.$name.'</td>'
                .'<td>'.number_format($sum,4).'</td>'
                .'<td align="right">'.$count.'</td>'
                .'<td align="right">'.number_format($emalloc).'</td>'
                .'<td align="right">'.number_format($realmem).'</td>'
                .'</tr>'
            ;
        }
        $out .= '</table>';
        $out .= '<pre>';
        $out .= print_r(Varien_Profiler::getSqlProfiler(Mage::getSingleton('core/resource')->getConnection('core_write')), 1);
        $out .= '</pre>';
        $out .= '</div>';

        return $out;//输出的内容是当前系统的一些信息，可以反映系统的健康状况
    }
}
