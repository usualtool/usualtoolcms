<div class="clear"></div>
 <div id="cmsfooter">
 <div id="footer">
  <div class="line"></div>
  <ul>
   CopyRight © 2018-<?php echo date('Y');?> UsualToolCMS (UTCMS) Quick Website Framework.
  </ul>
 </div>
</div>
<div class="clear"></div> 
</div>
<script type="text/javascript">
    if($("#ut-table").length>0){
        $(document).ready(function() {  
                    $('#ut-table').DataTable({  
                        dom: 'Bfrtip',
                        processing:true,
                        searching:false, 
                        ordering:true,
                        paging:false,
                        info:false,
                        lengthchange:false,
                        pagination:false,
                        serverside:false,
                        deferrender:true,
                        sorting: [[0, 'sorting']],
                        language: {
                            "paginate":{"next":"上页","previous":"下页"},
                            "sProcessing":"载入中...",
                            "sLengthMenu" : "显示 _MENU_ 条",  
                            "sSearch": "<i class='fa fa-search'></i> ",
                            "sZeroRecords": "无数据",
                            "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录",
                            "sInfoFiltered": "共 _MAX_ 条记录）", 
                            "sInfoEmpty": "当前显示0到0条，共0条记录",
                        },
                       "buttons": [  
                               {'extend':'copyHtml5','text':'复制记录','exportOptions':{'modifier': {'page': 'current'}}},
                               {'extend':'excelHtml5','text':'导出Excel','title':'<?php echo$modname;?>-'+randomnumber()+'','exportOptions':{'modifier':{'page': 'all'}}},
                               {'extend':'csvHtml5','text':'导出Csv','title':'<?php echo$modname;?>-'+randomnumber()+'','exportOptions':{'modifier':{'page': 'all'}}},
                               {'extend':'print','text': '打印','exportOptions': {'modifier': {'page': 'all'}}}
                           ]
                    });  
                });
    }
</script>
</body>
</html>