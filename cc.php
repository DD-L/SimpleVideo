<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head><body>
<p>
file: cc.php ;<br>clean cache, 清缓存程序: 可接受的参数为 limit, 单位MB, 如："./cc.php?limit=5"; 默认值为5MB.
<p>
<?php
// file: cc.php : clean cache, 清缓存程序, 
set_time_limit(0);
if (!class_exists('CacheList')) {
	die();
}

$max_limit_mb = 5; // 5mb
$dir = "./cache";

if (!empty($_GET['limit'])) {
	if (is_numeric($_GET['limit'])) {
		$max_limit_mb = $_GET['limit'];
	}
}

$cache_list = new CacheList();
list_file($dir);
clean_cache($max_limit_mb, $dir);


// function
function list_file($dir = './cache') {
	global $cache_list;
	$list = scandir($dir); 
	foreach($list as $file) {
		$file_location = $dir. "/". $file;
		if ($file == "." || $file == "..") {
			continue;
		}
		if (is_dir($file_location)) {
			list_file($file_location); 
		}
		else {
			$cache_list->insert($file_location);
		}
	}
}

function clean_cache($max_limit_mb = 5, $dir = './cache') {
	global $cache_list;
	echo "max limit: $max_limit_mb MB<p>before:";
	echo $len = $cache_list->cache_len() / 1024 / 1024;
	echo 'MB<p>';
	echo 'after:';
	$cache_list->remove_old_cache($max_limit_mb);//mb
	echo $len = $cache_list->cache_len() / 1024 / 1024;
	echo "MB";
	unset($cache_list);
}

?>


<?php
 
/**
 * @param PHP链表
 */
/**
*
*节点类
*/
class Node {
    private $Data;
    private $Next;
    public function setData($value) {
        $this->Data = $value;
    }
    public function setNext($value) {
         $this->Next = $value;
    }    
    public function getData() {
        return $this->Data;
    }
    public function getNext() {
        return $this->Next;
    }
    public function __construct($data,$next) {
        $this->setData($data);
        $this->setNext($next);
    }
};
//功能类
class LinkList {
    protected $header;//头节点
    protected $size;//长度
    public function getSize() {
        $i = 0;
        $node = $this->header;
        while ($node->getNext() != null) {
			$i++;
            $node = $node->getNext();
        }
		return $i;
    }
    public function setHeader($value) {
        $this->header = $value;
    }
    public function getHeader() {
        return $this->header;
    }
    public function __construct() {
        //header("content-type:text/html; charset=utf-8");
        $this->setHeader(new Node(null, null));
    }
    /**
     *@param  $data--要添加节点的数据
     */
    public function add($data) {
        $node = $this->header;
        while ($node->getNext() != null) {
            $node = $node->getNext();
        }
        $node->setNext(new Node($data, null));
    }
    /**
     *@param  $data--要移除节点的数据
     */
    public function removeAt($data) {
        $node = $this->header;
        if ($node->getNext() == null) {
			return;
		}
		//$preNode = $node;
        while ($node->getData() != $data) {
			$preNode = $node;
            $node = $node->getNext();
            if ($node == null) {
				return;
            }
        }
        
        $preNode->setNext($node->getNext());
        unset($node);
        //$node->setData($node->getNext()->getData());
    }
    /**
     *@param  遍历
     */
    public function display() {
        $node = $this->header;
        if ($node->getNext() == null) {
            //print("list is empty!");
            return;
        }
        while ($node->getNext() != null) {
            print($node->getNext()->getData());
            echo " + ";
            if($node->getNext()->getNext() == null) {
				break;
			}
            $node = $node->getNext(); 
        }  
        echo "<p>";
    }

    
    /**
	 *@param  $value--需要更新的节点的原数据  --$initial---更新后的数据
     */
    public function update($initial, $value) {
        $node = $this->header->getNext();
        if ($node->getNext() == null) {
            //print("数据集为空!");
            return;
        }
        while ($node->getData() != $data) {
            if ($node->getNext() == null) {
				break;
			}
            $node = $node->getNext();
        }
        $node->setData($initial);     
    }  
};

class CacheList extends LinkList {
	public function insert($data) {
		$node = $this->header;
		if ($node->getNext() == null) {
			$this->add($data);
            return;
        }
        $preNode = $node;
        $node = $node->getNext();
		while (filemtime($data) >= filemtime($node->getData())) {
			if ($node->getNext() == null) {
				$node->setNext(new Node($data, null));
				return;
			}
			$preNode = $node;
			$node = $node->getNext();
		}
		$preNode->setNext(new Node($data, $preNode->getNext()));
	}
	
	public function remove($data) {
		
		if (preg_match('/\/index\.((htm)|(html)|(php)|(asp)|(jsp))$/ims', $data) > 0 ) {
			return false;
		}
		if (@unlink($data)) {
			$this->removeAt($data);
			return true;
		}
		return false;
	}
	public function cache_len() { // byte
		$len = 0;
		$node = $this->header;
        if ($node->getNext() == null){
            return $len;
        }
        while ($node->getNext() != null) {
            $len += filesize($node->getNext()->getData());
            if($node->getNext()->getNext() == null) {
				break;
			}
            $node = $node->getNext(); 
        } 
		return $len;
	}
	
	// $max_len_mb [MB]
	public function remove_old_cache($max_len_mb) {
		$node = $this->header;
		if ($node->getNext() == null) {
			return;
		}
		$node = $node->getNext();
		$total_len = $this->cache_len();
		$cur_len = $total_len;
		while ($cur_len > $max_len_mb * 1024 * 1024) {
			if ($node->getNext() == null) {
				return;
			}
			$cache_tmp_len = filesize($node->getData());
			if ($this->remove($node->getData()) == true) {
				$cur_len = $cur_len - $cache_tmp_len;
			}
			$node = $node->getNext();
		}
    }
};
?>
</body></html>