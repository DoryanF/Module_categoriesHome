
<div class="products row featureCategories">
    <h2>FEATURED CATEGORIES</h2>
    {foreach from=$tabCateg item=$category }
        <a href="{$category['url']}">
            <div class="js-product product col-lg-{$large_device} col-md-{$medium_device} col-sm-{$small_device} divContentCategoryFeature">
                {if $displayImage == 1}
                    <div style="text-align: center;">
                    <img src="{$category['image']}" style="width: {$width}; height: {$height};"/>
                    </div>
                    
                {/if}

                {if $displayTitle == 1}
                    <h3>{$category['name']}</h3>
                {/if}


                {if $displayDescription == 1}
                    <p>{$category['description']}</p>
                {/if}
            </div>
        </a>
        
    {/foreach}
    
</div>