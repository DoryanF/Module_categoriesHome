
<div class="products row featureCategories">
    <h2>FEATURED CATEGORIES</h2>
    <div class="container-fluid">
        <div class="row categoriesContainer">
            {foreach from=$tabCateg item=$category }
                <a href="{$category['url']}">
                    <div class="col-lg-{$large_device} col-md-{$medium_device} col-sm-{$small_device} divContentCategoryFeature">
                        {if $displayImage == 1}
                            <div style="text-align: center;">
                            <img src="{$category['image']}" style="width: {$width}; height: {$height};"/>
                            </div>
                            
                        {/if}

                        {if $displayTitle == 1}
                            <div>
                                <h3>{$category['name']}</h3>
                            </div>
                            
                        {/if}


                        {if $displayDescription == 1}
                            <p>{$category['description']}</p>
                        {/if}
                    </div>
                </a>
            {/foreach}

        </div>
    </div>
    
</div>