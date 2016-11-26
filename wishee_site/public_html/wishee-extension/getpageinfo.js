
function DOMtoString(document_root) {
    var html = document.getElementsByTagName('body')[0].innerHTML;
    return html;
}

chrome.runtime.sendMessage({
    action: "getSource",
    source: DOMtoString(document)
});