

<div class="form-group">
    <select class="js-example-basic-single"  name="test" style="width:250px">
    <!--<select class="js-example-tags"  data-select2-tag="true" name="kitap_ismi" style="width:250px">-->

    </select>
</div>


<script>
    function set_select2(donem) {
      
      $(".js-example-basic-single").select2({
              //tags: true,
              placeholder: "Kitap ismi giriniz",
              minimumInputLength: 1,
              //allowClear: true,
              ajax: {
                  async: true,
                  url: '/d_donemleri/getSelectData',
                  type: "post",
                  data: function (params) {

                      var query = {
                          word: params.term
                      }
                      return query;
                  },
                  processResults: function (data) {
                      console.log(data)
                      return {
                          results: JSON.parse(data)
                      };
                  },
                  createTag: function (params) {
                      var term = $.trim(params.term);

                      if (term === '') {
                          return null;
                      }

                      return {
                          id: term,
                          text: term,
                          isNew: true
                      };
                  },
              }
          });


  
  
  }
</script>


