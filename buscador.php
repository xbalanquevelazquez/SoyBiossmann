    <style type="text/css">

    

    #searchcontrol .gsc-control { width : 500px; }
	.gsc-result-siteSearch{}
	.gsc-result-siteSearch	.gs-result{margin-bottom:30px;}
	.gsc-result-siteSearch	.gs-result		.gs-title{color:#003e78;}
	.gsc-result-siteSearch	.gs-result		div.gs-title{border-bottom:1px solid #EEE;}
	.gsc-resultsRoot-siteSearch .gsc-resultsHeader{border:0px none;margin-bottom:20px;}
	.gsc-resultsRoot-siteSearch .gsc-resultsHeader .gsc-twiddle{display:none;}
	.gsc-result-siteSearch	.gs-result		a.gs-title {text-decoration:none !important;font-weight:bold;}
	.gsc-result-siteSearch	.gs-result		.gs-title b{color:#fd7100;}
	.gsc-result-siteSearch	.gs-result		.gs-snippet{color:#565656;}
	.gsc-result-siteSearch	.gs-result		.gs-snippet b{color:#fd7100;}
	.gsc-result-siteSearch	.gs-result		.gs-visibleUrl-short{display:none;}
	.gsc-result-siteSearch	.gs-result		.gs-visibleUrl-long{display:block;color:#3983c4;}
	.gsc-cursor-box	.gsc-cursor .gsc-cursor-page{color:#3d6916 !important;}
	.gsc-cursor-box	.gsc-cursor .gsc-cursor-current-page{color:#666 !important;font-weight:bold !important;background:#DDD;padding:1px 3px;}
    .gsc-cursor-box a.gsc-trailing-more-results{color:#666 !important;}
	.gsc-branding{display:none}
	</style>
	
    <script src="http://www.google.com/jsapi" type="text/javascript"></script>

    <script type="text/javascript">
    //<![CDATA[
    
    google.load('search', '1');

    function OnLoad() {
      // Create a search control
      // for web search, sets it to large
        var searchControl = new google.search.SearchControl();
		
		//var sFormDiv = document.getElementById("searchForm");		
		//searchForm = new google.search.SearchForm(true, sFormDiv);
		
        // all searchers will run in large mode
        searchControl.setResultSetSize(GSearch.LARGE_RESULTSET);
		
		var siteSearch = new google.search.WebSearch();
		options = new google.search.SearcherOptions();
		siteSearch.setUserDefinedLabel("ESMASA");
		siteSearch.setUserDefinedClassSuffix("siteSearch");
		siteSearch.setSiteRestriction("esmasa.com");
		

		options.setExpandMode(GSearchControl.EXPAND_MODE_OPEN);
		
		searchControl.setLinkTarget(GSearch.LINK_TARGET_SELF);

		searchControl.addSearcher(siteSearch, options);
		
        
		var drawOptions = new google.search.DrawOptions();
        drawOptions.setDrawMode(google.search.SearchControl.DRAW_MODE_LINEAR);//DRAW_MODE_TABBED
        searchControl.draw(document.getElementById("searchcontrol"), drawOptions);
        
		searchControl.execute("<?php if(isset($_POST['txtBuscarTexto']) && $_POST['txtBuscarTexto'] != '') echo $_POST['txtBuscarTexto']; ?>");

    }
    
    google.setOnLoadCallback(OnLoad, true);

    //]]>
    </script>
    <div id="searchcontrol">Loading...</div>
