    <div id="menu">
        <ul>
            <li><a href='{site_url path="index"}' class="active">Home</a></li>
            <li><a href='{site_url path="news"}'>News</a></li>
            <li><a href='{site_url path="matches"}'>Matches</a></li>
            <li><a href='{site_url path="teams"}'>Teams</a></li>
            <li><a href='{site_url path="players"}'>Players</a></li>
            <li><a href='{site_url path="help"}'>Help</a></li>
            <li><a href='{site_url path="contact"}'>Contact</a></li>
            <li><a href='{site_url path="bans"}'>Bans</a></li>
            <li><a href='{site_url path="control-panel"}'>Control Panel</a></li>
        </ul>
    </div>
    <div id="page_navigation">
        {foreach from=$MenuItems item=item}
        <a href='{site_url path="page/show/{$item.Name}"}'>{$item.Name}</a>
        {/foreach}
    </div>