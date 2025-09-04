  <div class="w-full bg-white p-6 rounded-2xl shadow">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
        <h1 class="text-2xl font-semibold">Import Students (.xlsx)</h1>
        <p class="text-blue-800 py-1 px-4 text-sm rounded-sm bg-blue-200 text-center sm:text-right">
            See all
        </p>
    </div>

    <p class="text-sm text-gray-600 mb-4">
      Upload an Excel (.xlsx) file.
    </p>

    <form id="uploadForm" class="space-y-4">
      <div>
        <input type="file" id="file" name="file" accept=".xlsx" class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-gray-100 file:text-sm file:font-semibold" />
        <p id="error" class="text-red-500 hidden">Please choose a .xlsx file to upload.</p>
      </div>

      <div class="flex items-center gap-3">
        <button id="uploadBtn" type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Upload & Import</button>
        <button id="clearBtn" type="button" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">Clear</button>
        <div id="spinner" class="hidden text-sm text-gray-600">Importing…</div>
      </div>
    </form>

    <div id="result" class="mt-6"></div>
  </div>

<script>
$(function(){
  $('#clearBtn').on('click', function(){
    $('#file').val('');
    $('#result').html('');
    $('#error').addClass('hidden');
  });

  $('#uploadBtn').on('click', function(){
    var f = $('#file').prop('files')[0];
    if (!f) {
        $('#error').removeClass('hidden');
      return;
    }

    var fd = new FormData();
    fd.append('file', f);

    $('#uploadBtn').prop('disabled', true);
    $('#spinner').removeClass('hidden');
    $('#result').html('');

    $.ajax({
      url: 'import.php',
      type: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(resp){
        $('#spinner').addClass('hidden');
        $('#uploadBtn').prop('disabled', false);

        if (resp.success) {
          var html = '<div class="p-4 bg-green-50 rounded border border-green-200">';
          html += '<p class="font-medium text-green-700">Import completed</p>';
          html += '<p class="text-sm text-green-700 mt-1">Inserted: ' + resp.inserted + '</p>';
          if (resp.skipped && resp.skipped.length) {
            html += '<details class="mt-2"><summary class="text-sm text-gray-700">Skipped rows (' + resp.skipped.length + ')</summary>';
            html += '<ul class="text-xs text-red-600 mt-2">';
            resp.skipped.forEach(function(s){ html += '<li>' + s + '</li>'; });
            html += '</ul></details>';
          }
          html += '</div>';
          $('#result').html(html);
        } else {
          $('#result').html('<div class="p-4 bg-red-50 rounded border border-red-200"><p class="text-red-700 font-medium">Error: ' + resp.message + '</p></div>');
        }
      },
      error: function(xhr, status, err){
        $('#spinner').addClass('hidden');
        $('#uploadBtn').prop('disabled', false);
        $('#result').html('<div class="p-4 bg-red-50 rounded border border-red-200"><p class="text-red-700 font-medium">Upload failed: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : err) + '</p></div>');
      }
    });
  });
});
</script>
