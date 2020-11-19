function g(selector){
    var method = selector.substr(0,1) == '.' ?
      'getElementsByClassName' : 'getElementById';
    return document[method](selector.substr(1));
}
function export_pdf(pdf_container,student_id){ //引數是'#pdf_container' 或 '.pdf_container',注意帶字首
    console.log("匯出pdf")
    var export_file;
    $(pdf_container).addClass('pdf'); //pdf的css在下一個程式碼中,作用是使得列印的內容能在pdf中完全顯示
    //document.getElementsByClassName("teacher_descipt").style.wordBreak = "break-all"
    font = document.getElementsByTagName("font")
    for(var i = 0;i < font.length;i++){
      font[i].classList.add("wordwrap")
    }
    var cntElem = g(pdf_container);
    var shareContent = cntElem; //需要截圖的包裹的（原生的）DOM 物件
    var width = shareContent.offsetWidth; //獲取dom 寬度
    var height = shareContent.offsetHeight; //獲取dom 高度
    var pdf_containers = document.getElementsByClassName("pdf_container"+student_id); //取得頁面裡面要列印的每一個文件
    var split_height = new Array();
    for(var i = 0;i < pdf_containers.length;i++)
    {
      split_height[i] = console.log("PDF容器高度" , pdf_containers[i].offsetHeight)
    }
    var canvas = document.createElement("canvas"); //建立一個canvas節點
    var scale = 2; //定義任意放大倍數 支援小數
    canvas.width = width * scale; //定義canvas 寬度 * 縮放，在此我是把canvas放大了2倍
    canvas.height = height * scale; //定義canvas高度 *縮放
    canvas.getContext("2d").scale(scale, scale); //獲取context,設定scale 
    
    html2canvas(g(pdf_container), {
      allowTaint: true,
          taintTest: true,
          canvas: canvas,
      onrendered: function(canvas) {
        
      var context = canvas.getContext('2d');
      // 【重要】關閉抗鋸齒
      context.mozImageSmoothingEnabled = false;
      context.webkitImageSmoothingEnabled = false;
      context.msImageSmoothingEnabled = false;
      context.imageSmoothingEnabled = false;
        
        var imgData = canvas.toDataURL('image/jpeg',1.0);//轉化成base64格式,可上網瞭解此格式
        var img = new Image();
        img.src = imgData;
        img.onload = function() {	
          img.width = img.width/2;   //因為在上面放大了2倍，生成image之後要/2
          img.height = img.height/2;
          img.style.transform="scale(0.5)";
        console.log("img.width"+img.width);
        console.log("this.width="+this.width);
        console.log("this.height="+this.height);
        /*
        if (this.width > this.height) {//此可以根據列印的大小進行自動調節
          var doc = new jsPDF('l', 'mm', [this.width * 0.255, this.height * 0.225]);
        } else {
          var doc = new jsPDF('p', 'mm', [this.width * 0.255, this.height * 0.225]);
        }
        doc.addImage(imgData, 'jpeg', 10, 0, this.width * 0.225, 3000 * 0.225);
        doc.save('PDF_' + student_id + '.pdf');
        */
        var pageHeight = 978;//一頁高度\
        console.log("分頁高度",pageHeight)
        var leftHeight = height * 0.75;//未列印內容高度
          var position = 0;//頁面偏移
          var imgWidth = width;
          //var imgHeight = 841.89;
          var imgHeight =   height;
          console.log("imgWidth="+imgWidth);
          console.log("imgHeight="+imgHeight);
           var doc = new jsPDF('p', 'pt', 'a4');
          if(pageHeight >= leftHeight){//不需要分頁，頁面高度>=未列印內容高度
            console.log("不需要分頁");
            doc.addImage(imgData, 'jpeg', 35, 0, imgWidth*0.75, imgHeight*0.75);
          }else{//需要分頁
            console.log("需要分頁");
            while(leftHeight>0){
            console.log("position="+position);
            console.log("leftHeight="+leftHeight);
            doc.addImage(imgData, 'JPEG', 35, position, imgWidth*0.75, imgHeight*0.75);
            leftHeight -= pageHeight; 
            position -= 1128; 
            //避免新增空白頁
            if(leftHeight > 0){
              console.log("新增空白頁");
              doc.addPage();
            }
            }
          }
            doc.save('form_' + student_id + '.pdf');//儲存為pdf檔案
        }
      },
        background: "#fff", //一般把背景設定為白色，不然會出現圖片外無內容的地方出現黑色，有時候還需要在CSS樣式中設定div背景白色
      });
    $('#pdf_container').removeClass('pdf');
    $(".bt-group").css("display","block")
    console.log("匯出結束")
}

