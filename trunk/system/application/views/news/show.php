        {$message}
        {if {$entries|@count} == 0}
            <div>No entries to display.</div>
        {else}
            {foreach from=$entries item=entry}
                <div id="item">
                    <div id="header">
                        <div id="author">
                            By: {$entry.author}
                        </div>
                        <div id="time">
                            {$entry.created|date_format:"%A %B %e %l:%S %P"}
                        </div>
                    </div>
                    <div id="data">
                        {$entry.message|bbcode2html}
                        {has_perm perm='EditPages'}
                            <br>
                            <br>
                            [<a href='{site_url path="news/edit/{$entry.id}"}'>Edit</a>]
                        {/has_perm}
                    </div>
                </div>
                <br>
        {/foreach}
        {/if}