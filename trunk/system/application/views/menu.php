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
        {section name=i loop=$MenuItems}
        {$Name = $MenuItems[i].Name}
        <a href='{site_url path="page/show/$Name"}'>{$Name}</a>
        {/section}
    </div>