/*
  var landlord_name 房東姓名
  var landlord_pnumber  房東電話號碼
  var student_addr 學生租屋地址
  var student_class 學生班級
  var student_ID  學號
  var student_name  學生姓名

  表單問題陣列
  var question = ["李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?"]

  學生回答正常或異常陣列
  var answer = [1,0,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1]

  判斷學生是否勾選異常 若有異常則顯示問題描述框
  function load_descript() 

  載入學生及房東基本資料填寫欄位
  function load_form()
*/

/*
                for(var i = 0;i < 10;i++)
                {
                question = ["李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?","李敬是醜男嗎?"]
                student_data = ["怪人王","87878787","A市地底","資工三甲",i,"葉彥良"]
                answer = [1,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1]
                student_dic[i] = {"question":question,"answer":answer,"student_data":student_data}
                console.log(student_dic[i])
                }
*/

var student_dic = new Array()
var question_table = new Array()


/* <div class="col-sm-12 mtop" id="left-content">
        <div class="col-sm-12 mtop"><label>訪問項目與情形</label></div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;1.環境整潔</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="0" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="0">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal0">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;2.讀書情形</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="1" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="1">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal1">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;3.與房東相處</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="2" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="2">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal2">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;4.賃居地點偏僻</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="3" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="3">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal3">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;5.交友情形</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="4" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="4">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal4">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;6.交通及生活機能</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="5" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="5">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal5">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;7.進出人員是否複雜?有無安全疑慮?</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="6" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="6">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal6">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;8.有無滅火器及會不會使用</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="7" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="7">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal7">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;9.電熱器(太陽能)</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="8" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="8">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal8">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;10.有無緊急逃生道及是否暢通</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="9" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="9">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal9">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;11.有無緊急照明燈</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="10" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="10">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal10">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
        <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;12.有無煙霧探測器</label><br> <label class="radio-inline"><input disabled="disabled" type="radio" name="11" checked="">良好</label> <label class="radio-inline"><input disabled="disabled" type="radio" name="11">異常</label>&nbsp; <button type="button" onclick="load_image('')" class="btn btn-primary btn-info" data-toggle="modal" data-target="#myModal11">查看圖片</button>
            <div class="form-group hid" name="textarea" id="dsp"> <label for="comment">描述異常狀況:</label> <textarea readonly="readonly" class="form-control" rows="2" id="comment" style="resize:none;"></textarea> </div>
        </div>
    </div>





    <div class="col-sm-12 mtop">
        <div class="col-sm-12 mtop">
            <div class="form-group"> <label>訪問要點及學生意見</label> <textarea class="form-control" style="height:100px;resize:none;" id="teacher_comment"></textarea> </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-12 bt-group" style="text-align:right"> <button class="btn btn-info" onclick="btnDownloadPageBypfd2('#pdf_container')">pdf列印</button> <button onclick="insert_description()" class="btn btn-info">儲存表單</button> </div>
    </div>
    <div class="col-sm-12" style="text-align:center">
        <ul class="pagination">
            <li class="form_page_li"><a href="#form_" onclick="get_form('next')">Previous</a></li>
            <li class="form_page_li"><a href="#form_" onclick="get_form('pre')">Next</a></li>
        </ul>
    </div>
</div>
</div> */

//判斷學生是否勾選異常 若有異常則顯示問題描述框

function set_pdf_height(obj_name){
    pdf_container = document.getElementById(obj_name)
    div = pdf_container.getElementsByClassName("pdf_element")
    height = 0;
    for(var i = 0;i < div.length;i++)
    {
      height += div[i].offsetHeight;
    }
    pdf_container.setAttribute("style","height:" + height)
}

function head_content(num){
  var content ='';
  content += '<div style="height:1123px;">\
    <div class="col-sm-12" style="text-align:center;"> <label>\
    <h2>國立虎尾科技大學 108 學年度第 一 學期<br>賃居校外學生輔導訪問記錄表</h2>\
    </label> </div>\
    <div class="col-sm-12 mtop">\
      <div class="col-sm-6 mtop">\
        <div class="input-group mtop"> <span class="input-group-addon">房東姓名</span> <input id="msg" type="text" class="form-control" value="' + student_dic[num][0][1] + '" name="msg" placeholder="Additional Info"> </div>\
      </div>\
      <div class="col-sm-6 mtop mtop">\
        <div class="input-group mtop"> <span class="input-group-addon">房東電話</span> <input id="msg" type="text" class="form-control" value="' + student_dic[num][0][2] + '" name="msg" placeholder="Additional Info"> </div>\
      </div>\
    </div>\
    <div class="col-sm-12 mtop">\
        <div class="col-sm-12 mtop">\
          <div class="input-group mtop"> <span class="input-group-addon">賃居地址</span> <input id="msg" type="text" class="form-control" value="' + student_dic[num][0][3] + '" name="msg" placeholder="Additional Info"> </div>\
        </div>\
    </div>\
    <div class="col-sm-12 mtop">\
      <div class="col-sm-2 mtop"><label>賃居學生</label> </div>\
      <div class="col-sm-3 mtop">\
        <div class="input-group col-sm-12 mtop"> <span class="input-group-addon ">班級</span> <input id="msg" type="text" class="form-control" value="' + student_dic[num][0][4] + '" name="msg" placeholder=""> </div>\
      </div>\
      <div class="col-sm-4 mtop">\
        <div class="input-group col-sm-12 mtop"> <span class="input-group-addon">學號</span> <input id="msg" type="text" class="form-control" value="' + student_dic[num][0][5] +'" name="msg" placeholder=""> </div>\
      </div>\
      <div class="col-sm-3 mtop">\
        <div class="input-group col-sm-12 mtop"> <span class="input-group-addon">姓名</span> <input id="msg" type="text" class="form-control" value="' + student_dic[num][0][6] + '" name="msg" placeholder=""> </div>\
      </div>\
    </div>'
  return content;
}

function set_comment_height(){
  var Comment =  document.getElementById("teacher_comment")
  var left_content = document.getElementById("left-content")
  height = left_content.offsetHeight
  Comment.setAttribute("style", "height:" + height.toString() +"px;")
}


function readURL(input,number) {
          console.log("call", input)
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
          photo = e.target.result;
      console.log(photo)
      $('#question'+number).attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}


//如果此問題有異常則回傳true
function check_question(questionId,answer){
  for(var i = 0;i < answer.length;i++)
  {
    if(answer[i][0] == questionId){
          console.log("異常")
      return true
    }
  }
}


function body_cntent(num){
    var content = "";
    content = '<div class="col-sm-12 mtop" id="left-content">\
    <div class="col-sm-12 mtop"><label>訪問項目與情形</label></div>'


    var answer = student_dic[num][1] //取得有異常的敘述和圖片位置陣列 [questionid,description,imgpath]
    for(var i =0;i < question_table.length;i++)
    {
      var normal = ""
      var abnormal = ""
      var dsp_hid = ""
      if(check_question(i.toString(),answer)){
          abnormal = "checked"
      }else {
          normal = "checked"
          dsp_hid = "hid"
      }
      content +='\
            <div class="col-sm-6 mtop radiotable"><label>&nbsp;&nbsp;' +(parseInt(question_table[i][0])+1) + question_table[i][1] + '</label><br>\
              <label class="radio-inline"><input  type="radio" name="'+num+'_'+(i+1)+'"' + normal +'>良好</label>\
              <label class="radio-inline"><input  type="radio" name="'+num+'_'+(i+1)+'"' + abnormal +'>異常</label>&nbsp;\
            </div>'
    }
    content += '</div>'

    //訪問要點及學生意見 跟 儲存送出鈕
    
    content += '<div class="col-sm-12 mtop">\
                  <div class="col-sm-12 mtop">\
                      <div class="form-group"> <label>訪問要點及學生意見</label>\
                      <textarea class="form-control" style="height:100px;resize:none;" id="teacher_comment">'+student_dic[num][0][7]  +'</textarea>\
                      </div>\
                  </div>\
                </div></div>'

    var q_count = 0;
    for(var i = 0;i <  question_table.length;i++)
    {
      if(check_question(i.toString(),answer)){
        if(q_count % 4 == 0) content += '<div style="height:1123px;">'
        content +='<div class="col-sm-12" style="margin-bottom:5px">\
        <div class="col-sm-6">\
          <img style="width:200px;height:200px;" src="'+student_dic[num][1][q_count][2]+'"></img>\
        </div>\
        <div class="col-sm-6">\
        <label style="margin-top:20px;">' + (i+1) + '、問題描述:'+ question_table[i][1]+'<br>' + student_dic[num][1][q_count][1] + '</label><br>\
        <br>\
      </div></div>'
        q_count++;
        if(q_count % 4 == 3) content += '</div>'
      }
    }
    if((q_count-1) % 4 != 3) content += '</div>'
    return content;
}


function pdf_post(){
    var mapForm = document.createElement("form");
    mapForm.target = "Map";
    mapForm.method = "post"; // or "post" if appropriate
    mapForm.action = "../pdf_temp/pdf_temp.php";
    for(var i = 0;i < student_dic.length;i++)
    {
      var student_dic_Input = document.createElement("input");
      student_dic_Input.type = "hidden";
      student_dic_Input.name = "Student_dic" + i;
      student_dic_Input.value = student_dic[i];
      console.log(student_dic[i])
      mapForm.appendChild(student_dic_Input);
    }


    var question_Input = document.createElement("input");
    question_Input.type = "hidden";
    question_Input.name = "Question";
    question_Input.value = question;
    mapForm.appendChild(question_Input);

    var answer_Input = document.createElement("input");
    answer_Input.type = "hidden";
    answer_Input.name = "Answer";
    answer_Input.value = answer;
    mapForm.appendChild( answer_Input);


    document.body.appendChild(mapForm);

    map = window.open("about:blank", "Map", "status=0,title=0,height=600,width=1300,scrollbars=1");

    if (map) {
                mapForm.submit();
    } else {
                alert('You must allow popups for this map to work.');
    }
}

function upload_image(){

              }

//載入學生及房東基本資料填寫欄位
function load_student_form(Student_form,Question_table){
      student_dic = Student_form
      question_table = Question_table
      console.log("表單數量:" , student_dic.length)
      var form_content = ""
      for(var i = 0;i < student_dic.length;i++)
      {
        console.log("start")
        form_content += head_content(i);
        form_content += body_cntent(i);
      }
      document.getElementById('student_form').innerHTML=form_content
      //set_pdf_height('pdf_container')
      //set_comment_height()
} 
