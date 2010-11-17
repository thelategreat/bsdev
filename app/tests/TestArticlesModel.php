<?php 

class TestArticlesModel extends UnitTest
{
  function setUp()
  {
    $this->CI->load->model('articles_model');
  }

	function testThis()
	{
    $res = $this->CI->articles_model->get_article_list();
		$this->assertTrue( $res->num_rows() > 0 );
	}
}

?>