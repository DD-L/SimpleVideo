># SimpleVideo

>#### SimpleVideo[alpha] demo: [宿主站点 - imzker.com](http://www.imzker.com)

>####使用方法, 以优酷为例:
<pre>
视频播放页: http://v.youku.com/v_show/id_XODMwNTUwMjIw.html
SimpleVideo: http://<font color="blue">imzker.com/</font>v.youku.com/v_show/id_XODMwNTUwMjIw.html
</pre>
>> 如果您使用的是*Chrome*浏览器，可安装*sv*插件，便捷的使用*SimpleVideo*.
>> ######插件安装地址: 
1. [宿主站点] [http://imzker.com/ab/ChromeExt](http://imzker.com/ab/ChromeExt)
2. [./ChromeExt/sv.crx](./ChromeExt/sv.crx)
3. [Chrome 网上应用商店店] 暂未提交


--------------------------------------------------------

>## 关于API中的token:
>>
1. SimpleVideo API: [https://github.com/DD-L/SimpleVideo-API.git](https://github.com/DD-L/SimpleVideo-API.git)

>>
2. 代码中的$TOKEN为敏感信息, 请到API提供者那里申请

>>>* /source/d-l.top.class.php 中的$TOKEN 请到 [D-L.top](http://d-l.top) 申请
>>>* /source/Flvxz.class.php 中的$TOKEN 请到 [flvxz.com](http://www.flvxz.com) 申请（友情提示：flvxz.com已阵亡）
>>>* /source/Id97.class.php 当前已不对外开放API

> 欲了解更多信息, 请访问[D-L.top](http://d-l.top)

-------------------------------------------------------

> ###SimpleVideo 特性：
>> 
1. 面向接口, 易于对音视频解析API站点的扩展.
2. 为便于使用SimpleVideo, 提供hrome *sv*插件; 插件无需干预可自动更新.
3. 数据缓存: 自动缓存; 缓存手动刷新; 缓存清理.
4. 支持在线观看.
5. 无源站点广告，链接对外暴露，可自由下载.
6. 支持url rewrite, 访问更加直观
7. *容我想一会儿* ^_^

---------------------------------------------------------

> ###Chrome sv插件options页:
>>
<pre>
<div style="line-height:50%">
<h3>Simple Video</h3>
<p>
示例视频地址： http://v.youku.com/v_show/id_XNzc0NzgzMTc2.html <br>
使用方法：<br>
http://<font color="#1530F9"><b>imzker.com/</b></font>v.youku.com/v_show/id_XNzc0NzgzMTc2.html <font color="#77776E">[使用条件: 无]</font><br>  
http://<font color="#E935B6"><b>g.cn/</b></font>v.youku.com/v_show/id_XNzc0NzgzMTc2.html <font color="#77776E">[使用条件: 须装此扩展]</font><br>
http://<font color="#E935B6"><b>n/</b></font>v.youku.com/v_show/id_XNzc0NzgzMTc2.html <font color="#77776E">[使用条件: 须装此扩展，且在路由器环境下使用，并修改hosts文件]</font><br>
<hr>
<br/>
嫌 imzker.com/ 太长? 安装此扩展即可使用 <font color="#E935B6"><b>g.cn/</b></font> 甚至 <font color="#E935B6"><b>n/</b></font> 来代替 <font color="#1530F9"><b>imzker.com/</b></font><br>
<br>
如果想使用 n/ 必须 在 hosts文件中添加：<br><br>
# 192.168.1.1 为路由器地址<br>
192.168.1.1 n<br><br>
<hr>

<div>
<h5>version 0.0.3</h5>
<div style="margin-left:40px">
	<div><span>1. 增加对链接的右键菜单支持, 无需在地址栏手动输入.</span>
		<div style="margin-left:40px">
			<img src="./ChromeExtenstion-dev/sv/image/v_0.0.3_wrong.png" height="250" width="230"/>
			<img src="./ChromeExtenstion-dev/sv/image/v_0.0.3_right.png" height="250" width="200"/>
		</div>
	</div>
</div>
<div>
<h5>version 0.0.2</h5>
<div style="margin-left:40px">
	<div><span>1. 在地址栏中头部添加 g.cn/ 或 n/ 即可使用 Simple Video.</span>
		<div style="margin-left:40px">
			<span>方法一:</span><img src="./ChromeExtenstion-dev/sv/image/v_0.0.2_imzker.com.png"/> <span>[使用条件: 无需安装此扩展]</span><br/>
			<span>方法二:</span><img src="./ChromeExtenstion-dev/sv/image/v_0.0.2_g.cn.png"/> <span>[使用条件: 须装此扩展]</span><br/>
			<span>方法三:</span><img src="./ChromeExtenstion-dev/sv/image/v_0.0.2_n.png"/> <span>[使用条件: 须装此扩展，且在路由器环境下使用，并修改hosts文件]</span><br/>
		</div>
	</div>
</div>
</div>
</pre>

---------------------------------------------------------

> #####[LICENSE](./LICENSE)
&copy; Deel@d-l.top | [d-l.top](http://d-l.top)
