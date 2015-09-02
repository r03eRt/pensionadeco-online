function Pager(tableName, itemsPerPage) {
    this.tableName = tableName;
    this.itemsPerPage = itemsPerPage;
    this.currentPage = 1;
    this.pages = 0;
    this.inited = false;
    
    this.showRecords = function(from, to) {        
        var rows = document.getElementById(tableName).rows;			
        // i starts from 1 to skip table header row
        for (var i = 1; i < rows.length; i++) {
            if (i < from || i > to)  
                rows[i].style.display = 'none';
            else
                rows[i].style.display = '';
        }
    }
    
    this.showPage = function(pageNumber) {
    	if (! this.inited) {
    		alert("not inited");
    		return;
    	}
		
        var oldPageAnchor = document.getElementById('pg'+this.currentPage);
        oldPageAnchor.className = 'pg-normal';
        
        this.currentPage = pageNumber;
        var newPageAnchor = document.getElementById('pg'+this.currentPage);
        newPageAnchor.className = 'pg-selected';
        
        var from = (pageNumber - 1) * itemsPerPage + 1;
        var to = from + itemsPerPage - 1;
		
		
        this.showRecords(from, to);
		
		var pgPrev1 = document.getElementById('pgPrev1');
		var pgPrev2 = document.getElementById('pgPrev2');
		var pgNext3 = document.getElementById('pgNext3');        
		var pgNext4 = document.getElementById('pgNext4');
        
        if (this.currentPage == this.pages)
            pgNext3.style.display = 'none';
        else
            pgNext3.style.display = '';
		if (this.currentPage == 1)
				pgPrev1.style.display = 'none';
			else
				pgPrev1.style.display = '';
		if (this.currentPage == this.pages)
            pgNext4.style.display = 'none';
        else
            pgNext4.style.display = '';
		if (this.currentPage == 1)
				pgPrev2.style.display = 'none';
			else
				pgPrev2.style.display = '';		
		//alert(this.currentPage);
    }   
    
    this.prev = function() {
        if (this.currentPage > 1)
            this.showPage(this.currentPage - 1);
    }
    
    this.next = function() {
        if (this.currentPage < this.pages) {
            this.showPage(this.currentPage + 1);
        }
    }                        
    
    this.init = function() {
        var rows = document.getElementById(tableName).rows;
        var records = (rows.length - 1);		
        this.pages = Math.ceil(records / itemsPerPage);
        this.inited = true;
    }
	
    this.showPageNav = function(pagerName, positionId) {
    	if (! this.inited) {
    		alert("not inited");
    		return;
    	}		
		var rows = document.getElementById(tableName).rows;
		if((rows.length)-1 > itemsPerPage )
		{
			var element = document.getElementById(positionId);    	
			
			var pagerHtml = '<a id="pgPrev1" class="pg-normal" href="" onclick="' + pagerName + '.prev();return false;" class="pg-normal">Prev</a>&nbsp;';
			pagerHtml += '<a id="pgPrev2" class="pg-normal" href="" onclick="' + pagerName + '.prev();return false;" class="pg-normal">&laquo;</a>';
			//alert(newPageAnchor);
			
			for (var page = 1; page <= this.pages; page++)
			{				
				pagerHtml += ' <a class="pg-normal" href="" id="pg' + page + '" onclick="' + pagerName + '.showPage(' + page + ');return false;">' + page + '</a> ';					
			}
			pagerHtml += '<a id="pgNext3" class="pg-normal" href="" onclick="'+pagerName+'.next();return false;" class="pg-normal">&raquo;</a> ';  
			pagerHtml += '<a id="pgNext4" class="pg-normal" href="" onclick="'+pagerName+'.next();return false;" class="pg-normal"> Next</a>'; 
			
			element.innerHTML = pagerHtml;
		}
    }
}