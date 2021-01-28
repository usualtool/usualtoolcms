/*!
 * usualtoolcms page break v2.0 (http://cms.usualtool.com)
 * _usualtoolcms_pagebreak_ 为usualtoolcms内容分页固定标签，内容中有此标签视为自动分页。
 * 需要引用的地方需要给内容节点加上 id="cmscontent"
 */
var pageCount = 1;
var regExp = /_usualtoolcms_pagebreak_/;
var saveContent;
var content,pageList;

$(document).ready(function(){
UeInitialize("#cmscontent","#pagebreak");
});
UeInitialize = function (id,page) {
var cTxt = $(id).html();
content = $(id);
pageList = $(page);
if (cTxt != null && regExp.test(cTxt)) {
saveContent = cTxt.split(regExp);

pageCount = saveContent.length;
}
window.UePageContent(1);
};
UePageContent = function (pageIndex) {
if (pageIndex >= 1 && pageIndex <= pageCount && saveContent != null && saveContent.length >= 0) {
var pageHtml = pageList;
if ((parseInt(pageIndex) - 1) <= saveContent.length) {
content.html(saveContent[parseInt(pageIndex) - 1]);
}
pageHtml.html("");
var pagebreakcss = document.getElementById('pagebreak');
pagebreakcss.style.cssText = 'border-top:2px dotted #555555;text-align:center;margin-top:10px;padding:10px;color:#3784cb;font-size:1rem;';
var innHtml = "页数:" + pageIndex + "/" + pageCount;
innHtml += "<a style='padding-left:10px;color:#5B5B5B;' target='_self' href='javascript:UePageContent(1)'>首页</a>";
if (pageIndex > 1) {
innHtml += "<a style='padding-left:10px;color:#5B5B5B;' target='_self' href='javascript:UePageContent(" + (parseInt(pageIndex) - 1) + ")'>上一页</a>";
}
if (pageIndex < pageCount) {
innHtml += "<a style='padding-left:10px;color:#5B5B5B;' target='_self' href='javascript:UePageContent(" + (parseInt(pageIndex) + 1) + ")'>下一页</a>";
}
innHtml += "<a style='padding-left:10px;color:#5B5B5B;' target='_self' href='javascript:UePageContent(" + pageCount + ")'>末页</a>";
pageHtml.html(innHtml);
}
}