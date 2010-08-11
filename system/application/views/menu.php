    <div id="menu">
        <ul>
            <li><a href='?p=index' class="active">Home</a></li>
            <li><a href='?p=news'>News</a></li>
            <li><a href='?p=matches'>Matches</a></li>
            <li><a href='?p=teams'>Teams</a></li>
            <li><a href='?p=players'>Players</a></li>
            <li><a href='?p=help'>Help</a></li>
            <li><a href='?p=contact'>Contact</a></li>
            <li><a href='?p=bans'>Bans</a></li>
            <li><a href='?p=cp'>Control Panel</a></li>
        </ul>
    </div>
    <div id="page_navigation">
        {section name=i loop=$MenuItems}
        {$Name = $MenuItems[i].Name}
        <a href="/page/show/{$Name}">{$Name}</a>
        {/section}
    </div>