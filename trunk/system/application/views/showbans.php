        <div id="page_title">
            <h3>Bans</h3>
        </div>
        <div id="page_navigation">
            <a href='{site_url path="bans"}'>View Entries</a>
            {if $canAddEntry == true}<a href='{site_url path="bans/new"}'>New Entry</a>{/if}
        </div>

        {if $post.action == 'edit' or $post.action == 'new'}
            {$validateMessage}
            <form method="POST">
                Author: <input type="text" name="author" value="{$author}">
                <br>
                Message:
                <br>
                <script type="text/javascript" src="global/bbeditor/ed.js"></script>
                <script type="text/javascript">AddBBCodeToolbar('message'); </script>
                <textarea cols=80 rows=20 name="message" id="message">{$message}</textarea>
                <input type="hidden" name="id" value="{$id}">
                <br><br>
                <input type="submit" value="Save">
            </form>
        {/if}
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