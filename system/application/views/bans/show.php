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
                        {if $canEdit == true}
                            <br>
                            <br>
                            [<a href='{site_url path="bans/edit/{$entry.id}"}'>Edit</a>]
                        {/if}
                    </div>
                </div>
                <br>
        {/foreach}
        {/if}