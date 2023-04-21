<?php 

require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
$domainDetails = ModProjectrequestformHelper::getDomainDetails();
$dynamicpages= UserprofileHelpersUserprofile::dynamicpages();
$domain = strtolower($domainDetails[0]->Domain);


// get labels
    
if(strpos($_SERVER['REQUEST_URI'], '/index.php/') !== false){
    $strplace = strpos($_SERVER['REQUEST_URI'], '/index.php/');
    $langplace = $strplace + 11;
    $language = substr($_SERVER['REQUEST_URI'],$langplace,2);
}


    $dynpage=array();
   foreach($dynamicpages as $dpage){
      $dynpage[$dpage->PageId]=array($dpage->PageDescription,$dpage->PageStatus,$dpage->PageId);
   }
   
    $res=Controlbox::getlabels($language);
    $assArr = [];
    foreach($res as $response){
    $assArr[$response['Id']]  = $response['Text'];
    
     }

?>

<link rel="stylesheet" type="text/css" href="<?php echo JUri::base(); ?>/components/com_userprofile/css/dataTables.bootstrap.min.css">
<!--<link rel="stylesheet" type="text/css" href="<?#php echo JUri::base(); ?>/components/com_userprofile/css/style_global.css">-->
<link rel="stylesheet" type="text/css" href="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/css/style.css">
<script type="text/javascript" src="<?php echo JUri::base(); ?>/components/com_userprofile/js/jquery.dataTables.min.js" ></script>
<script type="text/javascript" src="<?php echo JUri::base(); ?>/components/com_userprofile/js/dataTables.bootstrap.min.js" ></script>

<script>
    
       pdfFunction = function(doc) {
      doc.content.splice(0,1);
    	doc.pageMargins = [20,60,20,30];
					 doc.defaultStyle.fontSize = 12;
					 doc.styles.tableHeader.fontSize = 12;
						
						doc['header']=(function() {
							return {
								columns: [
								      {
										alignment: 'center', text: 'Inventory purchases',fontSize: 18,margin: [10,0]
									 }],
									margin: 20
							       }
						});
						var objLayout = {};
						objLayout['hLineWidth'] = function(i) { return .7; };
						objLayout['vLineWidth'] = function(i) { return .7; };
						objLayout['hLineColor'] = function(i) { return '#aaa'; };
						objLayout['vLineColor'] = function(i) { return '#aaa'; };
						objLayout['paddingLeft'] = function(i) { return 7; };
						objLayout['paddingRight'] = function(i) { return 7; };
					    doc.content[0].layout = objLayout;
			             }
</script>
 
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function(){
    
    // var previousPageText="<?#php echo Jtext::_('COM_USERPROFILE_PREVIOUS_PAGE');?>";
    // var nextPageText="<?#php echo Jtext::_('COM_USERPROFILE_NEXT_PAGE');?>";
    // var showEntText="<?#php echo $assArr['show_entries'];?>";
    // var searchText="<?#php echo $assArr['search'];?>";
    // var showingEntriesText="<?#php echo $assArr['show_entries'];?>";
    // var showingEmptyEntriesText="<?#php echo Jtext::_('COM_USERPROFILE_SHOWING_EMPTY_ENTRIES');?>";
    // var nodataText = "<?#php echo Jtext::_('COM_USERPROFILE_NO_DATA');?>";
    var previousPageText="<?php echo Jtext::_('COM_USERPROFILE_PREVIOUS_PAGE');?>";
    var nextPageText="<?php echo Jtext::_('COM_USERPROFILE_NEXT_PAGE');?>";
    var showEntText="<?php echo $assArr['show_entries']; ?>";
    var searchText="<?php echo $assArr['search'];?>";
    var showingEntriesText="<?php echo Jtext::_('COM_USERPROFILE_SHOWING_ENTRIES');?>";
    var showingEmptyEntriesText="<?php echo Jtext::_('COM_USERPROFILE_SHOWING_EMPTY_ENTRIES');?>";
    var nodataText = "<?php echo Jtext::_('COM_USERPROFILE_NO_DATA');?>";
    
    $joomla('#a_table').DataTable({

     select: true,
     dom: 'Blfrtip',
     lengthMenu: [10,25,75, 100],
     scrollX: "400px",

      dom: 'Bfrtip',
      buttons: 
      [{text: '<i class="fa fa-file-pdf-o btn btn-default"></i>',extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'A3',
      exportOptions: { 	
          columns:':visible' 
          },
	customize: pdfFunction
	},
      {extend: 'csvHtml5',text:  '<i class="fa fa-file-text-o btn btn-default"></i>',titleAttr: 'CSV' },
      { extend: 'excelHtml5', text:    '<i class="fa fa-file-excel-o btn btn-default"></i>',titleAttr: 'Excel'},
      'pageLength' ,
      ],
      "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
            "previous": previousPageText,
            "next": nextPageText
            }
        }
    });  
    
    //ready to shippage table
    
    $joomla('#j_table').DataTable({
      "ordering": false,
     select: true,
     dom: 'Blfrtip',
     lengthChange: true,
     lengthMenu: [10,25,75,100],
      dom: 'Bfrtip',
      buttons: 
      
      [{ text: '<i class="fa fa-file-pdf-o btn btn-default"></i>',extend: 'pdfHtml5',filename: 'Inventory Items',orientation: 'landscape',pageSize: 'A4',
            exportOptions: {
            rows: 'tr:not(.child_row)',
            columns: [1,2,3,4,5,6,7,8,9,10,11],
            //columns:':visible'
       },
       customize: pdfFunction
          
      },
      { extend: 'csvHtml5',text:      '<i class="fa fa-file-text-o btn btn-default"></i>',titleAttr: 'CSV', title: 'Inventory Items', exportOptions: {
            rows: 'tr:not(.child_row)',  columns: [1,2,3,4,5,6,7,8,9,10,11],
         }},
      {  extend: 'excelHtml5', text:    '<i class="fa fa-file-excel-o btn btn-default"></i>',titleAttr: 'Excel', title: 'Inventory Items',  exportOptions: {
            rows: 'tr:not(.child_row)',  columns: [1,2,3,4,5,6,7,8,9,10,11],
         } },
      'pageLength',
      ],
      "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
        }
      
    }); 

    $joomla('#u_table').DataTable({
      "ordering": false,
     select: true,
     dom: 'Blfrtip',
     lengthMenu: [10,25,75, 100],
    
   
      dom: 'Bfrtip',
      buttons: 
      [{ text: '<i class="fa fa-file-pdf-o btn btn-default"></i>',extend: 'pdfHtml5',filename: 'Hold Shipments',orientation: 'landscape',pageSize: 'A4',
         exportOptions: {
            rows: 'tr:not(.child_row)',
            columns: [1,2,3,4,5,6,7,8,9,10,11],
       },
           customize: pdfFunction
      },
      { extend: 'csvHtml5',text:      '<i class="fa fa-file-text-o btn btn-default"></i>',titleAttr: 'CSV',title: 'Hold Shipments', exportOptions: {
            rows: 'tr:not(.child_row)',  columns: [1,2,3,4,5,6,7,8,9,10,11],
         }},
      {  extend: 'excelHtml5', text:    '<i class="fa fa-file-excel-o btn btn-default"></i>',titleAttr: 'Excel',title: 'Hold Shipments', exportOptions: {
            rows: 'tr:not(.child_row)',  columns: [1,2,3,4,5,6,7,8,9,10,11],
         } },
      'pageLength',
      ],
      "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
        }
    }); 
     
  //n_table export files pre-alert table
  
     $joomla('#n_table').DataTable(
		{
		      "ordering": true,
              select: true,
              dom: 'Blfrtip',
              lengthMenu: [10,25,75, 100],
              dom: 'Bfrtip',
 			  "buttons": [
				{
				text: '<i class="fa fa-file-pdf-o btn btn-default"></i>',extend: 'pdfHtml5',filename: 'Inventory purchases',orientation: 'landscape',pageSize: 'A4',exportOptions: {
						columns:':visible'
			          },
				  customize: pdfFunction
				    
				}, 
				 {extend: 'csvHtml5',text:  '<i class="fa fa-file-text-o btn btn-default" ></i>',titleAttr: 'CSV',title:'Inventory purchases' },
                 { extend: 'excelHtml5', text:  '<i class="fa fa-file-excel-o btn btn-default">',titleAttr: 'Excel',title:'Inventory purchases' },
				'pageLength',],
				
			"language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
              }
			});
   //F_table
   $joomla('#F_table').DataTable(
		{
		      "ordering": true,
              select: true,
              dom: 'Blfrtip',
              lengthMenu: [10,25,75, 100],
              dom: 'Bfrtip',
 			  "buttons": [
				{
				text: '<i class="fa fa-file-pdf-o btn btn-default"></i>',extend: 'pdfHtml5',filename: 'Inventory purchases',orientation: 'landscape',pageSize: 'A5',exportOptions: {
						columns:':visible'
			          },
				  customize: pdfFunction
				    
				}, 
				 {extend: 'csvHtml5',text:  '<i class="fa fa-file-text-o btn btn-default" ></i>',titleAttr: 'CSV',title:'Inventory purchases' },
                 { extend: 'excelHtml5', text:  '<i class="fa fa-file-excel-o btn btn-default">',titleAttr: 'Excel',title:'Inventory purchases' },
				'pageLength',],
				
			"language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
              }
			});
 
 //N_table 
   
     $joomla('#N_table').DataTable({
     "ordering": false,
     select: true,
     dom: 'Blfrtip',
     lengthMenu: [10,25,75,100],
     dom: 'Bfrtip',
    
      buttons: 
      [{ extend:'pdfHtml5',text:  '<i class="fa fa-file-pdf-o btn btn-default"></i>',titleAttr: 'PDF',title:'Inventory purchases',
         exportOptions: {
                    rows: ':visible:not(.wrchild)',columns: [1,2,3,4,5],
                  }
      },
      {extend: 'csvHtml5',text:      '<i class="fa fa-file-text-o btn btn-default"></i>',titleAttr: 'CSV',title:'Inventory purchases',
          exportOptions: {
                    rows: ':visible:not(.wrchild)',columns: [1,2,3,4,5],
                   }
      },
      { extend: 'excelHtml5', text:    '<i class="fa fa-file-excel-o btn btn-default"></i>',titleAttr: 'Excel',title:'Inventory purchases',
      exportOptions: {
                    rows: ':visible:not(.wrchild)',columns: [1,2,3,4,5],
                   }},
      'pageLength' ,
      ],
      "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
        }
    });
    
  
     // COD Page table
    
     $joomla('#c_table').DataTable({
     select: true,
     dom: 'Blfrtip',
     lengthMenu: [10,25,75, 100],
     dom: 'Bfrtip',
      buttons: 
      [{ text:  '<i class="fa fa-file-pdf-o btn btn-default"></i>',extend: 'pdfHtml5',filename: 'COD Items',orientation: 'landscape',pageSize: 'A4',exportOptions: {columns: [1,2,3,4]}, 
      customize: pdfFunction
    },
	  {extend: 'csvHtml5',text:      '<i class="fa fa-file-text-o btn btn-default"></i>',titleAttr: 'CSV',title:'COD Items',exportOptions: {columns: [1,2,3,4]} },
      { extend: 'excelHtml5', text:    '<i class="fa fa-file-excel-o btn btn-default"></i>',titleAttr: 'Excel',title:'COD Items',exportOptions: {columns: [1,2,3,4]} },
      'pageLength' ,
      ],
      "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
             "previous": previousPageText,
             "next": nextPageText
            }
        }
    });
    
    // end

    $joomla('.export_table').DataTable({
     select: true,
    //  scrollX: true,
     dom: 'Blfrtip',
     lengthMenu: [10,25,75, 100],
     scrollX: "400px",
     dom: 'Bfrtip',
     buttons: 
      
     [{text: '<i class="fa fa-file-pdf-o btn btn-default"></i>',extend: 'pdfHtml5',filename: 'Invoice Details',orientation: 'landscape',pageSize: 'A3',exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10,11,12]},
				  customize: pdfFunction
      	 },
	{extend: 'csvHtml5',text:      '<i class="fa fa-file-text-o btn btn-default"></i>',title:'Invoice Details',titleAttr: 'CSV',orientation: 'landscape',
                pageSize: 'LEGAL',exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10,11,12]}},
      { extend: 'excelHtml5', text:    '<i class="fa fa-file-excel-o btn btn-default"></i>',title:'Invoice Details',titleAttr: 'Excel',orientation: 'landscape',
                pageSize: 'LEGAL',exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10,11,12]}},
      'pageLength' ,
      ],
      "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
        }
      
      
    });
    
	$joomla( "#view_image,#exampleModal2,#exampleModal1,#exampleModal,#inv_view,#shipdetailsModal,#logModal,#ord_delete,#ord_keep,#ord_return,#ord_ship,#ord_edit" ).on('shown', function(){
	 $joomla( "html" ).css("overflow","hidden");
	});	
	$joomla( "#view_image,#exampleModal2,#exampleModal1,#exampleModal,#inv_view,#shipdetailsModal,#logModal,#ord_delete,#ord_keep,#ord_return,#ord_ship,#ord_edit" ).on('hidden', function(){
	 $joomla( "html" ).css("overflow","visible");
	});	
	
	
	$joomla('#M_table').DataTable({ 
    "ordering": false,
     select: true,
     dom: 'Blfrtip',
     lengthMenu: [10,25,75, 100],
     dom: 'Bfrtip',
    //  scrollX: "400px",
      buttons: 
      	  [{text: '<i class="fa fa-file-pdf-o btn btn-default"></i>',extend: 'pdfHtml5',filename: 'Shiphistory Details',orientation: 'landscape',pageSize: 'A4',exportOptions: {
            rows: 'tr:not(.child_row)',
            columns: [1,2,3,4,5,6,7,8,9],
       },
       customize: pdfFunction
      },
      {extend: 'csvHtml5',text: '<i class="fa fa-file-text-o btn btn-default"></i>',title:'Shiphistory Details',titleAttr: 'CSV',orientation: 'landscape',
                pageSize: 'LEGAL', exportOptions: {
             rows: 'tr:not(.child_row)',
            columns: [1,2,3,4,5,6,7,8,9],
         } },
      { extend: 'excelHtml5', text:    '<i class="fa fa-file-excel-o btn btn-default"></i>',title:'Shiphistory Details',titleAttr: 'Excel',orientation: 'landscape',
                pageSize: 'LEGAL', exportOptions: {
            rows: 'tr:not(.child_row)',
            columns: [1,2,3,4,5,6,7,8,9],
         } },
      'pageLength' ,
      ],
      "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
        }

});
	$joomla('#W_table').DataTable({ 
    "ordering": false,
     select: true,
     dom: 'Blfrtip',
     lengthMenu: [10,25,75, 100],
     dom: 'Bfrtip',
      buttons: 
       [{text: '<i class="fa fa-file-pdf-o btn btn-default"></i>',extend: 'pdfHtml5',filename: 'Viewshipment Details',orientation: 'landscape',pageSize: 'A4',exportOptions: {	columns:':visible' },
				  customize: pdfFunction
    },
      {extend: 'csvHtml5',text:      '<i class="fa fa-file-text-o btn btn-default"></i>',titleAttr: 'CSV' },
      { extend: 'excelHtml5', text:    '<i class="fa fa-file-excel-o btn btn-default"></i>',titleAttr: 'Excel' },
      'pageLength' ,
      ],
      "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
        }

});
});

$joomla(".modal").modal({backdrop: 'static',keyboard: false,show: true});

</script>
  
  <!--<div class="modal fade" id="myAlertModal" role="dialog">-->
  <!--  <div class="modal-dialog">-->
    
  <!--     Modal content-->
  <!--    <div class="modal-content">-->
  <!--      <div class="modal-header">-->
  <!--        <button type="button" class="close" data-dismiss="modal">&times;</button>-->
  <!--        <h4 class="modal-title">Message Box</h4>-->
  <!--      </div>-->
  <!--      <div class="modal-body">-->
  <!--        <p id="error"></p>-->
  <!--      </div>-->
  <!--      <div class="modal-footer">-->
  <!--        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
  <!--      </div>-->
  <!--    </div>-->
      
  <!--  </div>-->
  <!--</div>-->

<!--export-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <!-- <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script> -->
  <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
  <script src=" https://cdn.datatables.net/buttons/2.3.2/js/buttons.colVis.min.js "></script>

        