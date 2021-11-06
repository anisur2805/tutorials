(function($){
 
    "use strict";
 
    function buildJsonURL(perPage){
 
        var jsonUrl = tuts_opt.jsonUrl;
        if (typeof(perPage) != 'undefined' && perPage != null){
            jsonUrl += '?per_page='+perPage;
        }
        return jsonUrl;
    }
 
    $('.recent-tuts-wrapper').each(function(){
 
        // 1. Create all the required variables
 
        var $this          = $(this),
            termFilter     = $this.find('.term-filter'),
            recentTuts     = $this.find('.recent-tuts'),
            layout         = (recentTuts.hasClass('grid')) ? 'grid' : 'list',
            perPage        = termFilter.data('per-page'),
            requestRunning = false;
 
        // 2. Term filter click event
 
        termFilter.find('a').on('click',function(e){
 
            /* 
                3. Prevent link default
                   Make sure that the previous AJAX request is not ranning at the moment
                   Set a new requestRunning
 
            */
            e.preventDefault(); 
 
            if (requestRunning) {return;} 
 
            requestRunning = true;
 
            // 4. Remove current tuts from the tuts list to append requested tuts later
 
            recentTuts.addClass('loading');
            recentTuts.find('li').remove();
 
            // 5. Collect current filter data and toggle active class
 
            var currentFilter     = $(this),
                currentFilterLink = currentFilter.attr('href'),
                currentFilterID   = currentFilter.data('filter-id');
 
            currentFilter.addClass('active').siblings().removeClass('active');
 
            // 6. Build the json AJAX call URL
 
            var jsonUrl = buildJsonURL(perPage);
 
            if (typeof(currentFilterID) != 'undefined' && currentFilterID != null){
                jsonUrl += '&tutorial_category='+currentFilterID;
            }
 
            // 7. Send AJAX request
 
            $.ajax({
                dataType: 'json',
                url:jsonUrl
            })
            .done(function(response){
 
                // 8. If success loop with each responce object and create tuturial output
 
                var output = '';
 
                $.each(response,function(index,object){
 
                    output += '<li>';
 
                        output += '<img src="'+object.tutorial_image_src+'" alt="'+object.title.rendered+'" />';
 
                        output +='<div class="tutorial-content">';
 
                            output +='<div class="tutorial-category">';
                                var tutorialCategories = object.tutorial_category_attr;
                                for (var key in tutorialCategories) {
                                    output += '<a href="'+tutorialCategories[key][1]+'" title="'+tutorialCategories[key][0]+'" rel="tag">'+tutorialCategories[key][0]+'</a> ';
                                }
                            output +='</div>';
 
                            if ( '' != object.title.rendered ){
                                output +='<h4 class="tutorial-title entry-title">';
                                    output += '<a href="'+object.link+'" title="'+object.title.rendered+'" rel="bookmark">';
                                        output += object.title.rendered;
                                    output += '</a>';
                                output +='</h4>';
                            }
 
                            if ( '' != object.excerpt.rendered && layout == 'grid'){
                                output +='<div class="tutorial-excerpt">'+object.excerpt.rendered.replace(/(<([^>]+)>)/ig,"")+'</div>';
                            }
 
                            output +='<div class="tutorial-tag">';
                                var tutorialTags = object.tutorial_tag_attr;
                                for (var key in tutorialTags) {
                                    output += '<a href="'+tutorialTags[key][1]+'" title="'+tutorialTags[key][0]+'" rel="tag">'+tutorialTags[key][0]+'</a> ';
                                }
                            output +='</div>';
 
                        output +='</div>';
 
                    output += '</li>';
 
                });
 
                // 9. If output is ready append new tuts into the tuts list
 
                if (output.length) {
                    recentTuts.append(output);
                    recentTuts.removeClass('loading');
                }
                 
            })
            .fail(function(response){
 
                // 10. If fail alert error message
 
                alert("Something went wront, can't fetch tutorials");
            })
            .always(function(response){
 
                // 11. Always reset the requestRunning to keep sending new AJAX requests
 
                requestRunning = false;
            });
 
            return false;
 
        });
 
    });
 
})(jQuery);