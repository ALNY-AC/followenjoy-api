<?php

/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月19日13:25:09
* 最新修改时间：2018年3月19日13:25:09
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####VIP类，用于会员分销返利的功能#####
* @author 代码狮
* 当用户为会员的时候，也就是说买了499礼品的时候就需要通过此类计算此用户的一切
*
*/

class VIP{
    
    /** @var Boolean $isDebug 是否开启当前对象的测试模式，
    * 开启后，会输出一些操作的测试数据，将会影响ajax的功能使用
    */
    public $isDebug = false;
    
    /** @var Boolean $isWriteDatabase 当将要保存数据到数据库的时候，是否结束操作（不保存到数据库中）
    * 关闭后，数据库将不会保存到数据库中，如果配合 $isDebug 使用，将会输出一些保存到数据库的操作信息。
    */
    public $isWriteDatabase=true;
    
    // ==============================================用户信息相关的属性==============================================
    
    /** @var String $userId 当前vip用户的id */
    private $userId = '';
    
    /** @var String $userName 当前vip用户的userName */
    private $userName = '';
    
    /** @var Object $userInfo 当前用户在数据库中的数据 */
    private $userInfo;
    
    /** @var Object $super 自己上级的对象 */
    private $super ;
    
    /** @var Array $subList 自己的下级列表 */
    private $subList=[] ;
    
    
    /** @var Object $vipConf 数据库中，当前等级用户的配置项 */
    private $vipConf;
    
    /** @var Float $money  当前会员的电子账户余额 */
    private $money = 0.00;
    
    
    
    /** @var Integer $level 会员的等级
    * 将会根据会员的等级初始化当前会员的返利等信息和数据
    * 0级：普通的游客，值为0，（游客）虽然普通的游客无法使用分销功能，但是仍然需要其上级使用，所以普通的游客一样可以创建此对象
    * 一级：会员，值为1，（会员）
    * 二级：经理，值为2，（银牌VIP）
    * 三级：总监，值为3，（金牌VIP）
    */
    private $level=1;
    
    //等级的名称
    public $levelName='';
    
    
    //==============================================购买商品相关的数据==============================================
    
    /**
    * @var Float $discount 打几折
    * 买东西的折扣比率，自购省钱的折扣，这里实际是小数，比如折扣是 12% ，但是无论在哪都必须储存为 0.12
    * 【优惠功能】
    * 公式为：
    * 最终的订单价格=实际订单价格-实际订单价格*折扣的比率
    * */
    public $discount = 0.1;
    
    
    /**
    * @var Float $discountRebate 下级打折省下的钱，返给自己的比率，百分比
    * 下级省下来的钱，返给自己的比率
    * 【返利功能】
    * 公式：a
    * 省下来的钱=实际订单价格*折扣的比率
    * 自己得到的钱=省下来的钱*省钱返利比率
    */
    private $discountRebate = 0.5;
    
    /**
    * @var Float $saleMoneyRebate 当下级卖出去一个商品后，自己可以得到的回扣，百分比
    * 【返利功能】
    * 公式：
    * 上级可以得到的回扣=下级一个卖出东西的价格*上级得到回扣的比率
    */
    private $saleMoneyRebate = 0.25;
    
    
    /**
    * @var Float $saleMoneyRebateSuper 当下级的下级卖出去一个商品后，自己可以得到的回扣，百分比
    * 【返利功能】
    */
    private $subSaleMoneyRebater = 0.2;
    
    
    /**
    * @var Float 当此用户不是会员的时候，分享商品可以得到的回扣，百分比
    */
    public $shareMoney=0.5;
    
    //==============================================团队管理奖金相关数据==============================================
    
    /**
    * @var Float $invitePeople 邀请一个B，并且B购买了499礼包后，得到的奖金，【邀人得钱奖】
    * 成功邀请一位自己直属的会员可以得到的奖金
    * 【团队奖励功能】 invitePeople
    */
    private $invitePeople  = 250.00;
    
    
    //金牌的
    private $subSilverInvitePeople = 60.00;//subSilverInvitePeople 下级所有银牌团队新增会员奖
    private $直属金牌团队新增会员奖 = 20.00;//directlyGoldInvitePeople 直属金牌团队新增会员奖
    
    
    //银牌的
    private $直属银牌团队新增会员奖 = 20.00;//directlySilverInvitePeople 直属银牌团队新增会员奖 InvitePeople
    
    //通用的
    private $directlyTeamInvitePeopleManage=175.00;//金牌收益：7，银牌收益：7   directlyTeamInvitePeopleManage 直属团队新增会员的管理奖
    private $directlyTeamInvitePeople=20.00;//directlyTeamInvitePeople 直属团队新增会员的奖金
    private $subManageRatio = 0.3;//subManageRatio 可以得到下级管理奖金的比率
    
    //==============================================属性区结束==============================================
    
    
    //用户传进来的配置项
    private $conf;
    
    function __construct ($conf=false,$sub){
        
        
        $this->conf=$conf;
        if($conf){
            $this->userId=$conf['userId'];
        }
        //获得数据库中用户的数据
        $this->getUserInfoFormDatabase();
        //获得数据库中vip的设置
        $this->getVipConf();
        //初始化此用户
        $this->init();
        
        
        if(isset($conf['isDebug'])){
            $this->setDebug($conf['isDebug']);
        }
        
        if($this->isDebug){
            ec (" >== 用户：【 $this->userName : $this->userId 】被创建");
        }
        
        //初始化此用户的上级
        $this->initSuper();
        
    }
    
    
    /**
    * 获得此vip的数据
    */
    public function getInfo(){
        return $this->vipConf;
    }
    
    //==============================================下面是一些初始化数据==============================================
    /**
    * 根据数据库中的配置项，初始化当前用户的数据，比如返利数据等
    */
    private function init(){
        //设置用户的钱为数据库中的钱
        $this->money=$this->userInfo['user_money']+0.00;
        $this->level=$this->userInfo['user_vip_level']+0;
        $this->userName=$this->userInfo['user_name'];
        
        //初始化返利数据
        
        $this->levelName=$this->vipConf['vip_name'];//
        $this->discount=$this->vipConf['discount'];//打几折
        $this->discountRebate=$this->vipConf['discountRebate'];//下级打折省下的钱，返给自己的比率，百分比
        $this->saleMoneyRebate=$this->vipConf['saleMoneyRebate'];//当下级卖出去一个商品后，自己可以得到的回扣，百分比
        $this->subSaleMoneyRebater=$this->vipConf['subSaleMoneyRebater'];//当下级的下级卖出去一个商品后，自己可以得到的回扣，百分比
        $this->shareMoney=$this->vipConf['shareMoney'];//当此用户不是会员的时候，分享商品可以得到的回扣，百分比
        $this->invitePeople=$this->vipConf['invitePeople'];//【邀人得钱奖】
        $this->subSilverInvitePeople=$this->vipConf['subSilverInvitePeople'];//下级所有银牌团队新增会员奖
        $this->directlyTeamInvitePeopleManage=$this->vipConf['directlyTeamInvitePeopleManage'];//直属团队新增会员的管理奖
        $this->directlyTeamInvitePeople=$this->vipConf['directlyTeamInvitePeople'];//直属团队新增会员的奖金
        $this->subManageRatio=$this->vipConf['subManageRatio'];//可以得到下级管理奖金的比率
        
        
    }
    
    /**
    * 初始化上级，调用此函数将会进入一个递归调用的流程，
    * 会一直向上寻找上级，并创建上级的对象，当上级不存在的时候停止。
    */
    private function initSuper(){
        
        //判断此用户有没有上级，要是没有上级了，或者上级等于 商家，就不继续创建
        $UserSuper=M('user_super');
        $where=[];
        $where['user_id']=$this->userId;
        $super=$UserSuper->where($where)->find();
        if($super){
            //当上级存在
            //存在就创建一个上级的对象
            
            $conf=[];
            $conf['userId']= $super['super_id'];
            
            // ec (" >== 用户：【 $this->userName : $this->userId 】 创建了一个上级对象：". $super['super_id']." ==< ");
            $super=new VIP($conf,$this);
            
            $this->setSuper($super);
            
            
        }else{
            //当上级不存，需要让此用户的上级等于平台
        }
        
    }
    
    /**
    * 获得当前用户在数据库中对应的数据
    */
    private function getUserInfoFormDatabase(){
        $User=M('user');
        $where=[];
        $where['user_id']=$this->userId;
        $this->userInfo=$User->where($where)->find();
    }
    
    /**
    * 从数据库获得vip的配置项
    */
    private function getVipConf(){
        //根据当前用户的级别，来获得对应的配置项
        $Vip=D('Vip');
        $where=[];
        $where['vip_level']=$this->userInfo['user_vip_level'];
        $this->vipConf=$Vip->where($where)->find();
    }
    
    /**
    * 获得当前用户在数据库中的数据
    */
    public function getUserInfo(){
        return $this->userInfo;
    }
    
    //==============================================下面是 get/set==============================================
    
    /**
    * 保存当前对象的钱到数据库中的操作。
    * 当返利或奖励进行结束后，需要调用此函数将当前的钱保存到数据库中。
    * 具体的钱数由外部计算，此函数仅仅是将数据保存到数据库中
    * 保存成功后返回状态
    */
    public function saveMoney(){
        
        $User=M('user');
        $where=[];
        $where['user_id']=$this->userId;
        $save=[];
        $save['user_money']=$this->money;
        
        if($this->isDebug){
            ec("【 $this->userName 】：  该用户当前的余额：$this->money ￥");
        }
        if($this->isWriteDatabase){
            $result=$User->where($where)->save($save);
        }
        return $User->_sql();
        if($result!==false){
            return true;
        }else{
            return false;
        }
        
    }
    /**
    * 设置用户的id
    * @param String userId 想要设置的id
    *  */
    public function setUserId($userId){
        $this->userId=$userId;
    }
    /**
    * 取用户的id
    * @return String userId 当前用户的id
    *  */
    public function getUserId(){
        return $this->userId;
    }
    
    /**
    * 设置用户的等级
    * @param Integer level 想要设置的等级
    *  */
    public function setLevel($level){
        $this->level=$level+0;
    }
    /**
    * 取用户的等级
    * @return Integer
    *  */
    public function getLevel(){
        return $this->level;
    }
    
    /**
    * 设置自己的上级对象
    *  @param Object $super 上级的对象
    **/
    public function setSuper($super){
        $this->super=$super;
    }
    
    /**
    * 获得自己的上级对象
    *  @return Object $super 上级的对象
    **/
    public function getSuper($super){
        return $this->super;
    }
    
    /**
    * 设置当前对象的 测试 状态，并且设置上级的测试状态，进入递归流程
    */
    public function setDebug($is=false){
        $this->isDebug=$is;
        if($this->super!=null){
            $this->super->setDebug($is);
        }
    }
    /**
    * 设置当前对象的 测试 状态，并且设置上级的测试状态，进入递归流程
    */
    public function setWriteDatabase($is=true){
        $this->isWriteDatabase=$is;
        if($this->super!=null){
            $this->super->setWriteDatabase($is);
        }
    }
    
    /**
    * 构建自己的下级列表
    **/
    public function initSubList(){
        
        //取得自己下级的列表。
        //查上下级表，当上下级表中的 super_id 等于当前用户的id的时候，就是当前用户的下线，
        //然后取得改用户的信息存到数组中
        $UserSub=M('user_super');
        $where=[];
        $where['super_id']=$this->userId;
        $subList=$UserSub
        ->table('c_user_super as t1,c_user as t2')
        ->field('t1.*,t2.*')
        ->where('t1.user_id = t2.user_id')
        ->where($where)
        ->select();
        $this->subList=$subList;
        if($this->isDebug){
            ec("【 $this->userId 】：  下级$this->discount ");
            dump($subList);
        }
        
    }
    /**
    * 获得自己的下级列表
    *  @return Array $subList 下级列表
    * 返回的数据为数组，元素为当前类创建的对象
    **/
    public function getSubList(){
        return $this->subList;
    }
    
    
    //==============================================省钱功能实现==============================================
    /**
    * 此函数为自购省钱，当需要获得自购省钱数据的时候，调用此函数，
    * 什么时候调用？
    * 当用户进入准备支付页面的，此时会需要显示当前订单的优惠、减免等信息，而减免数据由本函数返回。
    * 当用户点击了去支付，此时服务器需要再次计算一次价格，比如 最终用户支付价格=总价-优惠-减免，而这个减免数据，由本函数返回。
    * ====
    * 也就是说，本函数需要传入一个当前的价格，这个价格在外面计算，然后本函数返回一个打折掉的钱，而打折后的钱一样由外面计算
    * 注意：此函数仅返回数据，并不进行任何的实际改写操作，如果需要让上级得到返利的钱，需要调用相关函数。
    *
    * ====
    *
    * 最终的订单价格=实际订单价格-实际订单价格*折扣的比率
    *
    * @param Float money 想要打折的钱
    *
    * @return Flaot 返回打折掉的钱。
    *
    * 比如，传入 100￥，打折比率为0.2，那么返回的钱为 100*0.2
    *
    */
    
    public  function getDiscount($money){
        //打掉的钱=实际订单价格*折扣的比率
        $finalPrice=$money*$this->discount;
        //判断类型
        if($this->level>0){
            if($this->isDebug){
                ec("【 $this->userName 】：  该用户的省钱比率：$this->discount ");
                ec("【 $this->userName 】：  该用户可以剩下的钱：$finalPrice ￥");
            }
            return  $finalPrice + 0.00;
        }else{
            return 0.00;
        }
        
    }
    
    /**
    * 此函数为真正执行省钱操作的函数，每次订单只能调用一次！
    * 当调用此函数后，会计算上级可以得到的返利，然后打入到上级的账户上，重复调用将会重复计算！
    * ===
    * 什么时候调用？
    * 当用户支付成功后，开始进入返利的流程调用，
    * @param Float money 订单的原本总价
    *
    */
    public function discountRebate($orderMoney){
        /*
        流程：
        当用户支付成功后，创建此用户的vip对象。
        调用此 函数，并传入订单的原本总价，此函数会自动 得到 折扣的钱
        然后调用此vip对象的上级的 getSubDiscountRebate() 函数，并传入 折扣的钱。
        接下来上级获得返利的操作由 getSubDiscountRebate() 完成。
        */
        if($this->isDebug){
            ec("【 $this->userName 】：  【 当前用户购物时候的优惠 】");
            ec("【 $this->userName 】：  该用户当前的余额：$this->money ￥");
            ec("【 $this->userName 】：  订单总价为：$orderMoney ￥");
        }
        
        
        $money=$this->getDiscount($orderMoney);//计算省下来的钱
        return  $this->super->getSubDiscountRebate($money);//调用上级的函数进行省钱返利操作
        
    }
    
    /**
    * 此函数为 获得下级折扣返利 的函数，
    * 此函数必须由下级调用，并且传入下级省去的钱，
    * 然后此函数会根据当前vip用户的返利比率计算最终所得利润，并且返回状态。
    * @param Flaot $subDiscountRebate 下级省下的钱
    */
    public function getSubDiscountRebate($subDiscountRebate){
        
        if($this->isDebug){
            ec("【 $this->userName 】：  【 得到下级购物打折的钱 】");
            ec("【 $this->userName 】：  该用户当前的余额：$this->money ￥");
        }
        //自己能得到的钱
        $myMoney=0;
        //公式：
        //自己得到的钱=省下来的钱*省钱返利比率
        $myMoney=$subDiscountRebate*$this->discountRebate;
        //先让此对象的 $money 加上 $myMoney。
        $this->money+= $myMoney;
        
        if($this->isDebug){
            ec("【 $this->userName 】：  该用户获得下级省去的钱的比率：$this->discountRebate ");
            ec("【 $this->userName 】：  下级省去的钱：$subDiscountRebate ￥");
            ec("【 $this->userName 】：  上级得到了这些钱：$myMoney ￥");
        }
        
        //然后保存到数据库中
        return $result=$this->saveMoney();
        
    }
    
    
    
    //==============================================销售奖功能实现==============================================
    
    
    /**
    * 销售奖的计算函数
    * 调用此函数后将会计算奖金并且写入到数据库中，订单只能调用一次！
    * ===
    * 什么时候调用？
    * 当分享出去的商品被购买后，先取得分享人的 vip 对象，然后取得分享人 vip 对象的 super，
    * 然后调用这个 super 的 salesAward() 函数。
    * @param Flaot $orderMoney 订单的金额
    */
    public function salesAward($orderMoney){
        //公式
        // 上级可以得到的回扣=下级一个卖出东西的价格*上级得到回扣的比率
        
        $money=$orderMoney*$this->saleMoneyRebate;
        
        if($this->isDebug){
            ec("【 $this->userName 】：  【 下级分享商品被购买后，自己可以得到的钱 】");
            ec("【 $this->userName 】：  该用户当前的余额：$this->money ￥");
            ec("【 $this->userName 】：  该用户获得下级销售奖的比率：$this->saleMoneyRebate ");
            ec("【 $this->userName 】：  订单总价为：$orderMoney ￥");
            ec("【 $this->userName 】：  该用户获得下级的销售奖金：$money ￥");
        }
        
        //计算完成，设置数据后保存
        $this->money+=$money;
        $this->saveMoney();
        //在这里找到上级，并且让上级得到当前用户得到的 20%
        
        //先判断有没有上级
        if($this->super){
            
            $super=$this->super;
            
            if($super->level>1){
                //上级的等级是银牌或金牌，可以继续操作
                if($this->isDebug){
                    ec("【 $this->userName 】：  找到上级 【 $super->userName 】，并且权限足够， 可以继续执行操作。");
                }
                $super->layersOfRebate($money);
                
            }else{
                if($this->isDebug){
                    ec("【 $this->userName 】：  虽然当前用户有上级，但是上级的权限不足，上级为： 【 $super->userName 】，等级：$super->level");
                }
            }
            
        }else{
            if($this->isDebug){
                ec("【 $this->userName 】：  此用户没有上级");
            }
        }
        
        
        
    }
    
    /**
    * 此函数为获得销售返利用户的上级的上级所得奖励
    * @param Float $money 下级得到的销售返利的钱
    */
    public function layersOfRebate($subMoney){
        if($this->isDebug){
            ec("【 $this->userName 】：  【 得下级销售返利的回扣 】");
            ec("【 $this->userName 】：  该用户当前的余额：$this->money ￥");
            ec("【 $this->userName 】：  下级得到的返利：$subMoney ￥");
            ec("【 $this->userName 】：  当前用户可以得到下级返利的比率：$this->subSaleMoneyRebater");
        }
        
        //公式
        //当前用户得到的回扣=下级得到的回扣*当前用户得到回扣的比率
        $myMoney=$subMoney*$this->subSaleMoneyRebater;
        $this->money+=$myMoney;
        if($this->isDebug){
            ec("【 $this->userName 】：  当前用户可以得到的返利$myMoney ￥");
        }
        $this->saveMoney();
        
    }
    
    //==================团队管理奖金相关功能============================
    
    /**
    * 查找上级，传入当前用户和想要查找的级别
    * 只返回第一个匹配的
    */
    public function querySuper($level){
        
        ec ("> 找人模式 < 谁在找人：$this->userName");
        
        if($this->getSuper()){
            
            if($this->getSuper()->level==$level){
                //条件满足，返回数据
                $t=$this->getSuper()->userName;
                ec ("> 找人模式 < 找到的人：$t 条件满足");
                return $this->getSuper();
            }else{
                return $this->getSuper()->querySuper($level);
            }
            
        }else{
            //没有上级
            return null;
        }
    }
    
    /**
    * 【邀人得钱奖】
    * 当自己的团队成功的发展了一名会员（购买了499）后，调用。只能一次！重复调用将会重复计算！
    * ===
    * 什么时候调用？
    * 当会员成功购买了499后，取得这个会员的 vip 对象，然后取得这个 vip 对象的 super，之后调用 super 的此函数
    *
    * 这里将会计算当前用户可以得到多少奖金，并且保存到数据库中
    *
    */
    public function 邀请人得钱奖($sub){
        // invitePeople
        if($this->isDebug){
            ec("【 $this->userName 】：  【 邀人得钱奖 】");
            ec("【 $this->userName 】：  该用户当前的等级：$this->level");
            ec("【 $this->userName 】：  当前用户为邀请人，当前用户邀请了：$sub->userName");
            ec("【 $this->userName 】：  该用户当前的余额：$this->money ￥");
            ec("【 $this->userName 】：  该用户可以得到的【 邀人得钱奖 】：$this->invitePeople ￥");
        }
        
        //先让自己得钱
        $this->money+=$this->invitePeople;
        $this->saveMoney();
        
        //==============================================更多的流程==============================================
        //无论咋样，先看看有没有上级银牌，让上级银牌的上级得钱
        
        
        //当前用户是一级VIP的 【邀人得钱奖】
        if($this->level==1){
            
            if($this->getSuper()){
                //存在上级
                
                if($this->getSuper()->level>=2){
                    //如果上级是银牌或者金牌
                    $this->getSuper()->得到直属团队新增会员的管理奖($this);
                    
                    if($this->getSuper()->getSuper()){
                        $this->getSuper()->getSuper()->得到直属团队新增会员奖20($this->getSuper());
                    }
                    
                }
                
                if($this->getSuper()->level==1){
                    //向上递归查找
                    $silverSuper=$this->querySuper(2);
                    if($silverSuper){
                        //如果上级银牌存在
                        //判断这个银牌有没有上级
                        if($silverSuper->getSuper()){
                            $silverSuper->getSuper()->得到直属团队新增会员奖20($silverSuper);
                        }
                    }else{
                        //没有银牌，
                        //再看看金牌存不存在
                        $goldSuper=$this->querySuper(3);
                        if($goldSuper){
                            //如果上级金牌存在
                            $goldSuper->得到直属团队新增会员奖20($this);
                        }
                    }
                    
                }
                
            }
            
        }
        
        //银牌 的 【邀人得钱奖】
        if($this->level==2){
            //如果有上级
            //无论咋样，只要银牌的团队新增了人，上级的第一个金牌就得钱
            //找金牌
            $goldSuper=$this->querySuper(3);
            if($goldSuper){
                //如果上级金牌存在
                $goldSuper->得到下级所有银牌团队新增会员奖60();
            }
            
            if($this->getSuper()){
                //有上级
                //如果上级是银牌
                if($this->getSuper()->level==2){
                    //获得 得到直属银牌团队新增会员奖20
                    $this->getSuper()->得到直属团队新增会员奖20($this);
                }
                
            }
            
        }
        
        //当前用户是金牌的 【邀人得钱奖】
        if($this->level==3){
            
            if($this->getSuper()){
                //存在上级
                //金牌的上级肯定是金牌，所以不用判断
                $this->getSuper()->得到直属团队新增会员奖20($this);
            }
            
        }
        
        
    }
    
    
    /**
    * 当下级所有银牌团队有会员加入，当前金牌就能得到60
    */
    public function 得到下级所有银牌团队新增会员奖60($sub){
        //虽然之前判断过了，这里还要判断一次
        if($this->level==3){
            //是金牌
            if($this->isDebug){
                ec("【 $this->userName 】：  【 下级所有银牌团队新增会员奖 】");
                ec("【 $this->userName 】：  该用户当前的余额：$this->money ￥");
                ec("【 $this->userName 】：  该用户当前的等级：$this->level");
                ec("【 $this->userName 】：  该用户可以得到的【 下级所有银牌团队新增会员奖 】：$this->subSilverInvitePeople ￥");
            }
            
            $this->money+=$this->subSilverInvitePeople;
            $this->saveMoney();
            
        }else{
            return false;
        }
        
    }
    
    
    /**
    * 当自己直属的银牌团队新增了一个人，自己能得到20
    */
    public function 得到直属团队新增会员奖20($sub){
        
        
        //判断下级的级别 >=2 并且 当前用户的级别等于下级的级别，才有此收益
        if($this->level>=2 && $this->level==$sub->level){
            //是银牌
            if($this->isDebug){
                ec("【 $this->userName 】：  【 得到直属团队新增会员奖20 】");
                ec("【 $this->userName 】：  该用户当前的余额：$this->money ￥");
                ec("【 $this->userName 】：  该用户当前的等级：$this->level");
                ec("【 $this->userName 】：  该用户可以得到的【 直属团队新增会员的奖金 】：$this->directlyTeamInvitePeople ￥");
            }
            
            $this->money+=$this->directlyTeamInvitePeople;
            $this->saveMoney();
            
        }else{
            return false;
        }
        
    }
    
    /**
    * 自己直属的团队要是有新人加入，自己可以得到管理奖金
    */
    public function 得到直属团队新增会员的管理奖($sub){
        
        //管理奖
        if($this->level>=2){
            //如果是银牌
            if($this->isDebug){
                ec("【 $this->userName 】：  【 得到直属团队新增会员的管理奖 】");
                ec("【 $this->userName 】：  该用户当前的余额：$this->money ￥");
                ec("【 $this->userName 】：  该用户当前的等级：$this->level");
                ec("【 $this->userName 】：  该用户可以得到的【 直属团队新增会员的管理奖 】：$this->directlyTeamInvitePeopleManage ￥");
            }
            $this->money+=$this->directlyTeamInvitePeopleManage;
            $this->saveMoney();
            $this->getSuper()->得到管理奖金的百分之30($this->directlyTeamInvitePeopleManage,$sub);
        }
        
    }
    
    
    
    /**
    * 【管理奖金*30奖】
    *
    * 调用这个函数后，当前用户将会得到 管理奖金*30%
    *
    * @param Float $subMoney 下级得到的钱
    * @param Vip $sub 调用此函数的下级
    */
    public function 得到管理奖金的百分之30($subMoney,$sub){
        
        $myMoney=$subMoney*$this->subManageRatio;
        
        if($this->isDebug){
            ec("【 $this->userName 】：  【 得到管理奖金的百分之30 】");
            ec("【 $this->userName 】：  该用户当前的余额：$this->money ￥");
            ec("【 $this->userName 】：  该用户当前的等级：$this->level");
            ec("【 $this->userName 】：  下级得到的奖金：$subMoney ￥");
            ec("【 $this->userName 】：  该用户可以得到的【 可以得到下级管理奖金的比率 】：$this->subManageRatio");
            ec("【 $this->userName 】：  该用户可以得到的奖金：$myMoney ￥");
        }
        
        $this->money+= $myMoney;
        $this->saveMoney();
        
        
        //银牌得到了管理奖金*30%，上级金牌也要得到。
        //金牌得到了管理奖金*30%，直接上级金牌也要得到。
        
        if($this->getSuper()){
            
            $super=$this->getSuper();
            if($super->level==3){
                //如果上级就是金牌
                $super->得到管理奖金的百分之30($myMoney);
            }else{
                //上级不是金牌，
                //就向上找，直到找到金牌，然后让他得钱
                $super=$this->querySuper(3);
                if($super){
                    //上级金牌存在，
                    //让上级金牌得钱
                    $super->得到管理奖金的百分之30($myMoney);
                }
            }
            
            
        }else{
            if($this->isDebug){
                ec("【 $this->userName 】：当前用户没有上级");
            }
            
        }
        return $myMoney;
        
        
        
    }
    
    public function getShareMoney(){
        
        return $this->shareMoney;
    }
    
    
    
    
    
}