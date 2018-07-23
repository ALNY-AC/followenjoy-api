<?php
class VipPlus{
    
    
    public $level=0;                // ==> 当前vip级别。默认0，代表无级别普通客户
    public $userId='';               // ==> 当前用户的id
    public $userName='';               // ==> 当前用户的用户名
    public $money=0;                // ==> 当前用户的钱
    public $course_hours=0;                // ==> 当前用户的钱
    public $isDebug=false;          // ==> 是否测试模式
    public $isSave=false;           // ==> 是否保存到数据库中
    public $userInfo;               // ==> 用户数据
    private $User;                  // ==> 用户的数据库对象
    private $Profit;                //收支模型
    private $super;                 //此用户上级
    private $vipConf;                 //vip配置
    private $conf;                 //vip配置
    private $vip_name="普通用户";       //vip的名字
    private $subList=[];             //下级列表
    private $subListIds=[];             //下级列表 的 id
    private $subVipList=[];         //下级vip对象的列表
    
    // ===================================================================================
    // 会员返利数据
    public $佣金收益比=0;
    public $佣金收益比返利比=0;
    public $邀人得钱奖=0;
    public $同级N层节点新增奖=0;
    public $管理奖金=0;
    public $管理奖金收益比=0;
    public $N层经理节点新增奖=0;
    public $销售收益奖金比=0;
    public $管理奖金收益的收益比=0;
    
    
    public function VipPlus ($conf=false,$sub){
        $this->conf=$conf;
        // ===================================================================================
        // 初始化变量
        $this->User=D('User');//初始化用户模型
        $this->Profit=D('Profit');//初始化用户收支模型
        if($conf){
            $this->userId=$conf['userId'];//取得用户id
            $this->isDebug=$conf['isDebug']===null?true:$conf['isDebug'];//测试模式
            $this->isSave=$conf['isSave']===null?true:$conf['isSave'];//是否将数据保存到数据库中
        }
        
        // ===================================================================================
        // 取出用户信息
        $this->updateUserInfo();//用户信息
        $this->updateVipData();//vip配置
        $this->init();//初始化
        
        if($this->isDebug){
            ec (" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】被创建");
        }
        
        // ===================================================================================
        // 初始化此用户的上级
        $this->initSuper();
        // 初始化自己下级列表`
        $this->initSubList();
        
        
    }
    /**
    * 更新用户信息，从数据库中
    */
    public function updateUserInfo(){
        // ===================================================================================
        // 取出用户信息
        $where=[];
        $where['user_id']=$this->userId;
        $user=$this->userInfo=$this->User->where($where)->find();
        
        
    }
    
    /**
    * 更新vip数据
    */
    public function updateVipData(){
        
        $Vip=D('Vip');
        $where=[];
        $where['vip_level']=$this->userInfo['user_vip_level'];
        $this->vipConf=$Vip->where($where)->find();
        
    }
    
    public function init(){
        
        // ===================================================================================
        // 配置易用变量
        $this->level=$this->userInfo['user_vip_level']+0.00;//用户vip等级
        $this->money=$this->userInfo['user_money']+0;//用户余额
        $this->course_hours=$this->userInfo['course_hours']+0;//课时
        $this->userName=$this->userInfo['user_name'];//用户名
        
        $this->vip_name=$this->vipConf['vip_name'];//
        
        $this->佣金收益比=$this->vipConf['佣金收益比'];//
        $this->佣金收益比返利比=$this->vipConf['佣金收益比返利比'];//
        $this->邀人得钱奖=$this->vipConf['邀人得钱奖'];//
        $this->同级N层节点新增奖=$this->vipConf['同级N层节点新增奖'];//
        $this->管理奖金=$this->vipConf['管理奖金'];//
        $this->管理奖金收益比=$this->vipConf['管理奖金收益比'];//
        $this->管理奖金收益的收益比=$this->vipConf['管理奖金收益的收益比'];//
        $this->N层经理节点新增奖=$this->vipConf['N层经理节点新增奖'];//
        $this->销售收益奖金比=$this->vipConf['销售收益奖金比'];//
        
    }
    /**
    * 获得此vip的数据
    */
    public function getInfo(){
        return $this->vipConf;
    }
    
    public function initSuper(){
        $UserSuper=D('UserSuper');
        $where=[];
        $where['user_id']=$this->userId;
        $super=$UserSuper->where($where)->find();
        if($super){
            //当上级存在
            //存在就创建一个上级的对象
            $conf=$this->conf;
            $conf['userId']= $super['super_id'];
            $super=new VipPlus($conf,$this);
            $this->setSuper($super);
            
        }else{
            //当上级不存，需要让此用户的上级等于平台
        }
        
    }
    
    /**
    * 设置自己的上级对象
    *  @param Object $super 上级的对象
    **/
    public function setSuper($super){
        $this->super=$super;
    }
    /**
    * 取得自己上级的对象
    */
    public function getSuper(){
        return $this->super;
    }
    /**
    * 创建新用户收支明细
    */
    public function createProfit($money,$type,$log){
        $data['user_id']=$this->userId;
        $data['type']=$type;//1：收入，2：支出
        $data['money']=$money;
        $data['log_info']=$log;
        
        if($this->isDebug){
            ec (" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】新建用户收支：");
            dump($data);
        }
        
        if($this->isSave){
            return $this->Profit->create($data);
        }
    }
    
    /**
    * 自购：判断是否有分享人id，如果没有，判断是不是会员，如果自己是会员，则调用这个会员的此函数。
    * 分享：判断是否有分享人id，如果有，判断分享人是不是会员，如果是，调用分享人（会员）的此函数。
    *
    * 会员自购：会员自己购买了商品后，传入销售佣金
    * 会员分享：会员卖掉一个商品后，传入销售佣金
    *
    * 不管外面咋调用，都会让$this的user 得到钱
    *
    * @var Float commision 销售佣金
    *
    */
    public function 出货得佣金($佣金){
        // 这里是会员
        
        // 虽然外面已经判断了，但是还要判断一下自己是否是会员
        if($this->level>0){
            // 是会员
            
            if($this->isDebug){
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：【 出货得佣金 】");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】得到销售佣金：$佣金 ￥");
            }
            $this->money+=$佣金;
            $this->saveMoney();
            $this->createProfit($佣金,'收入',"[销售佣金]:获得销售佣金 [$佣金] ￥");
            
            if($this->level>=1){
                // 获直属会员的销售佣金50%，所以要判断当前是会员，上级才能得到钱
                if($this->getSuper()){
                    $this->getSuper()->得佣金收益($佣金);
                }
            }
        }
        
    }
    
    public function 得佣金收益($佣金){
        // 百分比奖金
        
        $佣金收益=($佣金*$this->佣金收益比);
        $this->money+=$佣金收益;
        
        if($this->isDebug){
            
            ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：【 得佣金收益 】");
            ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】得到佣金收益比：$this->佣金收益比");
            ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】得到佣金收益：$佣金收益 ￥");
            
        }
        
        $this->saveMoney();
        $this->createProfit($佣金收益,'收入',"[佣金收益]:获得佣金收益 [$佣金收益] ￥");
        
        if($this->level==2){
            // 如果是经理
            // 上级的最近的一个总监得到30%
            
            // 当自己是经理，直接上级是总监的情况下，这个总监是得到课时费，还是佣金*25%*20%
            // 这里放到个人账户里面
            $super=$this->querySuper(3);
            if($super){
                // 得到佣金收益的30%
                // 总监获得所有层经理的佣金收益返利（佣金收益奖金）
                // 个人账户的30%
                
                
                $super->money+=$佣金收益*0.3;
                $super->saveMoney();
                
                // $super->总监获得所有层经理的佣金收益返利（佣金收益奖金）($佣金收益);
                
            }
            
        }
        
        if($this->level==1 && $this->getSuper()){
            if($this->getSuper()->level>=2){
                $this->getSuper()->总监或经理得到佣金收益奖金($佣金收益);
            }
        }
        
    }
    
    
    public function 总监或经理得到佣金收益奖金($佣金收益){
        // 佣金收益比返利比
        if($this->level>=2){
            $佣金收益的返利=$佣金收益*$this->佣金收益比返利比;
            if($this->isDebug){
                
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  【 总监课时费 】");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户当前的余额：$this->money ￥");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 佣金收益比返利比 】：$this->佣金收益比返利比");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 佣金收益的返利 】：$佣金收益的返利 ￥");
            }
            $this->money+= $佣金收益的返利;
            $this->saveMoney();
            $this->createProfit($佣金收益的返利,'收入',"[佣金收益的返利]:获得佣金收益的返利(佣金收益奖) [$佣金收益的返利] ￥");
            if($this->getSuper() && $this->getSuper()->level==3){
                // 保存到课时里
                $this->getSuper()->总监获得所有层经理的佣金收益返利（佣金收益奖金）($佣金收益的返利);
            }
        }
        
        
    }
    
    public function 总监获得所有层经理的佣金收益返利（佣金收益奖金）($佣金奖金收益){
        
        // 存到单独账户
        if($this->level==3){
            
            $课时费=$this->管理奖金收益的收益比*$佣金奖金收益;
            $this->course_hours+=$课时费;
            
            if($this->isDebug){
                
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  【 总监获得所有层经理的佣金收益返利（佣金收益奖金）】");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户当前的余额：$this->money ￥");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 管理奖金收益的收益比 】：$this->管理奖金收益的收益比 ");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 课时费 】：$课时费 ￥");
                
            }
            
            $this->saveCourseHours();
            $this->createProfit($课时费,'收入',"[课时]:得到课时 [$课时费] ￥");
            
        }
        
    }
    
    // 保存用户的钱
    private function saveMoney(){
        $where=[];
        $where['user_id']=$this->userId;
        $save=[];
        $save['user_money']=$this->money;
        if($this->isDebug){
            ec(">== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】该用户当前的余额：$this->money ￥");
        }
        if($this->isSave){
            $result=$this->User->where($where)->save($save);
        }
        
        if($result!==false){
            return true;
        }else{
            return false;
        }
    }
    
    private function saveCourseHours(){
        
        
        $where=[];
        $where['user_id']=$this->userId;
        $save=[];
        $save['course_hours']=$this->course_hours;
        if($this->isDebug){
            ec(">== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】该用户当前的余额：$this->course_hours ￥");
        }
        if($this->isSave){
            $result=$this->User->where($where)->save($save);
        }
        
        if($result!==false){
            return true;
        }else{
            return false;
        }
        
    }
    
    
    /**
    * 直接邀请得钱奖，
    * 当用户购买499后，创建邀请人vip对象，和购买499买家的vip对象，并且将499vip对象传入
    */
    public function 获得邀人得钱奖($sub){
        // 先让$sub 写入关联表中
        // ===================================================================================
        // 先保存到关联表中
        $this->linkUser($sub);
        
        // ===================================================================================
        // 先让自己得到钱
        
        if($this->isDebug){
            
            ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  【 获得邀人得钱奖 】");
            ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户当前的余额：$this->money ￥");
            ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 邀人得钱奖 】：$this->邀人得钱奖 ￥");
            
        }
        
        $this->money+=$this->邀人得钱奖;
        $this->saveMoney();
        $this->createProfit($this->邀人得钱奖,'收入',"[直邀会员]:获得直邀会员收益 [$this->邀人得钱奖] ￥");
        
        // 一切先从当前为会员开始
        if($this->level==1){
            
            //一切先从有上级开始
            if($this->getSuper()){
                
                // ===================================================================================
                // 先找  同级N层节点新增奖 的受益人
                // 如果当前用户是会员
                // ===================================================================================
                // 先找经理
                
                
                if($this->querySuper(2)){
                    // 存在经理
                    $is = $this->找N层节点新增奖受益人(2);//经理受益人
                }else{
                    $this->找N层节点新增奖受益人(3);//经理受益人
                }
                
                // ===================================================================================
                // N层节点找完
                
                // ===================================================================================
                // 如果直接上级是经理，且直接上级的上级还是经理
                $superLevel=$this->getSuper()->level;
                if($superLevel==2 || $superLevel==3){
                    // 上级得到管理奖金
                    $this->getSuper()->获得管理奖金();
                }
                
            }
            
        }
        if($this->level==2){
            
            // N层经理节点新增奖
            $N层上=$this->querySuper(3);
            if($N层上){
                $N层上->获得N层经理节点新增奖();
            }
        }
        
        $this->检测升级条件();
        
    }
    
    public function 获得N层经理节点新增奖(){
        if($this->level==3){
            
            if($this->isDebug){
                
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  【 获得N层经理节点新增奖 】");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户当前的余额：$this->money ￥");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 N层经理节点新增奖 】：$this->N层经理节点新增奖 ￥");
                
            }
            
            $this->money+=$this->N层经理节点新增奖;
            $this->saveMoney();
            $this->createProfit($this->N层经理节点新增奖,'收入',"[N层经理节点新增奖]:获得N层经理节点新增奖 [$this->N层经理节点新增奖] ￥");
            
        }
        
    }
    
    
    public function 获得管理奖金(){
        // 自己得到管理奖金
        
        // ===================================================================================
        // 先判断自己条件是否满足
        
        $level=$this->level;
        if($level==2 || $level==3){
            // 如果自己是经理或者总监，自己可以得到钱
            
            if($this->isDebug){
                
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  【 获得管理奖金 】");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户当前的余额：$this->money ￥");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 获得管理奖金 】：$this->管理奖金 ￥");
                
            }
            
            $this->money+=$this->管理奖金;
            $this->saveMoney();
            $this->createProfit($this->管理奖金,'收入',"[管理奖金]:获得管理奖金 [$this->管理奖金] ￥");
            
            // 有管理奖金，就要查找一下是否有人可以获得 管理奖金收益
            // 首先让同样的上级取得收益
            if($this->getSuper()->level===$this->level){
                $this->getSuper()->管理奖金收益($this->管理奖金);
            }
            
        }
        
        
    }
    
    public function 管理奖金收益($管理奖金){
        
        $管理奖金收益=$管理奖金*$this->管理奖金收益比;
        
        // 判断当前用户是不是经理或者总监
        if($this->level==3 || $this->level==2){
            // 当前对象是经理或者总监，可以获得管理奖金收益`
            $this->money+=$管理奖金收益;
            
            if($this->isDebug){
                
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  【 管理奖金收益比 】");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户当前的余额：$this->money ￥");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 管理奖金收益比 】：$this->管理奖金收益比 ");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 管理奖金收益 】：$管理奖金收益 ￥");
                
            }
            
            $this->saveMoney();
            $this->createProfit($管理奖金收益,'收入',"[管理奖金收益]:获得管理奖金收益 [$管理奖金收益] ￥");
            
        }
        if($this->level==2){
            // 管理奖金收益的收益
            $总监=$this->querySuper(3);
            
            if($总监){
                // 上面有个总监
                // 让这个总监得到 管理奖金收益的收益
                $总监->总监获得所有层经理的佣金收益返利（佣金收益奖金）($管理奖金收益);
                
                
            }
            
        }
        
    }
    
    public function 得到经理管理奖金收益的比（课时费）($管理奖金收益){
        
        // 存到单独账户
        if($this->level==3){
            $管理奖金收益的收益=$this->管理奖金收益的收益比*$管理奖金收益;
            $this->money+=$管理奖金收益的收益;
            
            if($this->isDebug){
                
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  【 得到经理管理奖金收益的比（课时费） 】");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户当前的余额：$this->money ￥");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 管理奖金收益的收益比 】：$this->管理奖金收益的收益比 ");
                ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 管理奖金收益的收益 】：$管理奖金收益的收益 ￥");
                
            }
            
            $this->saveMoney();
            // 总监获得所有层经理的佣金收益返利（佣金收益奖金）
            $this->createProfit($管理奖金收益的收益,'收入',"[管理奖金收益的收益]:得到经理管理奖金收益的比（课时费） [$管理奖金收益的收益] ￥");
            
        }
        
    }
    
    
    
    private function 找N层节点新增奖受益人($level){
        $N层上=$this->querySuper($level);
        if($N层上 && $N层上->getSuper()){
            if($N层上->getSuper()->level==$level){
                $N层上->getSuper()->获得同级N层节点新增奖();
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
        return false;
        
    }
    
    public function 获得同级N层节点新增奖(){
        
        if($this->isDebug){
            
            ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  【 同级N层节点新增奖 】");
            ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户当前的余额：$this->money ￥");
            ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户可以得到的【 同级N层节点新增奖 】：$this->同级N层节点新增奖 ￥");
            
        }
        
        $this->money+=$this->同级N层节点新增奖;
        $this->saveMoney();
        $this->createProfit($this->同级N层节点新增奖,'收入',"[直邀会员]:获得同级N层节点新增奖 [$this->同级N层节点新增奖] ￥");
        
    }
    
    public function linkUser($sub){
        $UserSuper=D('UserSuper');
        $where=[];
        $where['user_id']=$sub->userId;
        // $where['super_id']=$this->userId;
        $is=$UserSuper->where($where)->find();
        if(!$is){
            // 不存在
            $data=[];
            $data['user_id']=$sub->userId;
            $data['super_id']=$this->userId;
            $data['add_time']=time();
            $data['edit_time']=time();
            return $UserSuper->add($data);
        }else{
            // 存在
            // 覆盖
            $data=[];
            $where['user_id']=$sub->userId;
            $data['super_id']=$this->userId;
            $data['add_time']=time();
            $data['edit_time']=time();
            return $UserSuper->where($where)->save($data);
        }
    }
    
    
    /**
    * 查找上级，传入当前用户和想要查找的级别
    * 只返回第一个匹配的
    */
    public function querySuper($level){
        
        if($this->isDebug){
            ec ("> 找人模式 找 $level < 谁在找人：【$this->level | $this->vip_name | $this->userName : $this->userId 】");
        }
        
        if($this->getSuper()){
            
            if($this->getSuper()->level==$level){
                //条件满足，返回数据
                $t=$this->getSuper();
                if($this->isDebug){
                    ec ("> 找人模式 < 找到的人：===$t->level | $t->vip_name | $t->userName : $t->userId === 条件满足");
                }
                return $this->getSuper();
            }else{
                return $this->getSuper()->querySuper($level);
            }
            
        }else{
            //没有上级
            return null;
        }
    }
    
    
    public function test($info){
        $test=F('testinfo');
        $test=$test.'<br/>'.$info;
        F('testinfo',$test);
    }
    
    
    // ===================================================================================
    // 辅助脚本
    
    public function 检测升级条件(){
        
        // 执行完毕后，需要让上级也执行一次，自己等级发生变化，上级也要发生变化
        if($this->getSuper()){
            
            // 升级完毕，要检查是否比上级大，比上级大的话，就删除关联
            $myLevel=$this->level;
            $superLevel=$this->getSuper()->level;
            
            if($myLevel>$superLevel){
                $UserSuper=D('UserSuper');
                $where=[];
                $where['user_id']=$this->userId;
                $where['super_id']=$this->getSuper()->userId;
                $UserSuper->where($where)->delete();
            }
            
            $this->getSuper()->检测升级条件();
            
        }
        // 重新初始化下级列表
        
        $this->initSubList();
        $UpgradeLog=D('UpgradeLog');
        
        if($this->isDebug){
            ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  检测升级条件");
        }
        
        $subList=$this->getSubList();
        
        // ===================================================================================
        // 先判断当前用户等级
        
        if($this->level==1){
            
            // 如果当前用户是会员，检测升级到经理的条件
            // 检测此时此刻自己下面是否有25个人
            // 找到自己下面的所有的是会员的
            
            $下级会员列表=[];
            
            foreach ($subList as $k => $v) {
                if($v['user_vip_level']==1){
                    // 当下级是会员的时候，插入到数组中
                    $下级会员列表[]=$v;
                }
            }
            
            $直接下级会员数=count($下级会员列表);
            
            if($直接下级会员数>=25){
                // 可以升级,升级为经理
                $this->upgrade(2);
                $data=[];
                $data['user_id']=$this->userId;
                $data['log']='[等级升级]：从会员升级为经理';
                $UpgradeLog->create($data);
            }
            
        }
        
        if($this->level==2){
            // 当前用户等级是经理，需要走经理升级到总监的条件
            // 先判断直属经理
            
            $下级经理列表=[];
            foreach ($subList as $k => $v) {
                if($v['user_vip_level']==2){
                    // 当下级是会员的时候，插入到数组中
                    $下级经理列表[]=$v;
                }
            }
            
            if(count($下级经理列表)>=2){
                // 满足直属经理两人
                // 开始判断下面所有层会员的数量
                
                $会员列表=$this->递归二叉树找普通会员();
                
                if(count($会员列表)>=6){
                    // 升级成总监
                    $this->upgrade(3);
                    $data=[];
                    $data['user_id']=$this->userId;
                    $data['log']='[等级升级]：从经理升级为总监';
                    $UpgradeLog->create($data);
                }
            }
            
            
        }
        
        
        
    }
    
    public function 递归二叉树找普通会员(){
        
        
        $list=$this->initSubVipList();
        
        if(!$list){
            return [];
        }
        $会员列表=[];
        foreach ($list as $k => $v) {
            $会员列表2=$v->递归二叉树找普通会员();
            $会员列表=array_merge($会员列表,$会员列表2);
            if($v->level==1){
                $会员列表[]=$this->userId;
            }
        }
        return $会员列表;
    }
    
    public function initSubList(){
        //取得自己下级的列表。
        //查上下级表，当上下级表中的 super_id 等于当前用户的id的时候，就是当前用户的下线，
        //然后取得改用户的信息存到数组中
        $UserSub=D('UserSuper');
        
        $where=[];
        $where['super_id']=$this->userId;
        $ids=$UserSub->where($where)->getField("user_id",true);
        
        if($ids){
            $this->subListIds=getIds($ids);
            $userList= $this->User->where(['user_id'=>['in',$ids]])->select();
            $this->subList=$userList;
        }
        
        if($this->isDebug){
            // ec(" >== 用户【$this->level | $this->vip_name | $this->userName : $this->userId 】：  该用户的下级");
        }
    }
    
    public function getSubList(){
        return $this->subList;
    }
    
    
    /**
    * 初始化下级vip列表，一般不调用，用到才调用
    */
    public function initSubVipList(){
        $ids=$this->subListIds;
        
        $list=[];
        if($ids){
            // 有列表,一个个创建vip对象
            
            foreach ($ids as $k => $v) {
                $this->test("784：<pre>".json_encode($v)."</pre>");
                $conf=$this->conf;
                $conf['userId']=$v;
                $sub=new VipPlus($conf);
                $list[]=$sub;
            }
            $this->subVipList=$list;
            return $list;
        }else {
            return [];
        }
        return [];
    }
    
    
    // 让当前用户改变级别
    public function upgrade($level){
        $where=[];
        $where['user_id']=$this->userId;
        $data=[];
        $data['user_vip_level']=$level;
        return $this->User->where($where)->save($data);
    }
    
}