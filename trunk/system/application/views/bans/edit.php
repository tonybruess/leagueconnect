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