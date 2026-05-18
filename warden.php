
<html>
<head>
    <title>warden</title>
    <link rel="stylesheet" href="css/warden.css?v=2">
</head>
<body>
    <h1>warden</h1>

    <div class="actions">
        <div class="dlg-wrap">
            <button id="btnSub" type="button">Add Sub Warden</button>
            <div id="dlgSub" class="dialog" aria-hidden="true">
                <form method="post" action="">
                    <input type="text" name="subname" placeholder="Name" required>
                    <input type="text" name="subid" placeholder="Warden ID" required>
                    <button class="btn-save" type="submit" name="addsub">Save</button>
                </form>
            </div>
        </div>

        <div class="dlg-wrap">
            <button id="btnSec" type="button">Add Security</button>
            <div id="dlgSec" class="dialog" aria-hidden="true">
                <form method="post" action="">
                    <input type="text" name="secname" placeholder="Name" required>
                    <input type="text" name="secid" placeholder="Warden ID" required>
                    <button class="btn-save" type="submit" name="addsec">Save</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    (function(){
      const wrappers = document.querySelectorAll('.dlg-wrap');
      wrappers.forEach(w=>{
        const btn = w.querySelector('button');
        const dlg = w.querySelector('.dialog');
        btn.addEventListener('click', (e)=>{
          e.stopPropagation();
          // close others
          document.querySelectorAll('.dialog.show').forEach(d=>{ if(d!==dlg) d.classList.remove('show'); d.setAttribute('aria-hidden','true'); });
          dlg.classList.toggle('show');
          dlg.setAttribute('aria-hidden', dlg.classList.contains('show') ? 'false' : 'true');
        });

        dlg.addEventListener('click', e => e.stopPropagation());
      });

      // close on outside click
      document.addEventListener('click', ()=>{ document.querySelectorAll('.dialog.show').forEach(d=>{ d.classList.remove('show'); d.setAttribute('aria-hidden','true'); }); });

      // close on ESC
      document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') document.querySelectorAll('.dialog.show').forEach(d=>{ d.classList.remove('show'); d.setAttribute('aria-hidden','true'); }); });
    })();
    </script>

</body>

</html>