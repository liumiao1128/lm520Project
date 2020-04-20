@extends('Admin.layouts.master')
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <table class="layui-table">
                        <thead>
                        <tr>
                            <th>菜单名称</th>
                            <th>层级</th>
                            <th>排序</th>
                            <th>类型</th>
                            <th>action 地址</th>
                            <th>状态</th>
                            <th>备注</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($menuList as $key => $value){
                        $str_blank = '';
                        for ($i = 2; $i <= $value['level']; $i++) {
                            $str_blank .= '|--';
                        }
                        ?>
                        <tr>
                            <td><?php echo $str_blank;?><?php echo empty($value['MenuName']) ? '' : $value['MenuName']; ?></td>
                            <td><?php echo $value['level']?></td>
                            <td><?php echo empty($value['Sort']) ? 0 : $value['Sort']; ?></td>
                            <td>{{ $value['MenuType']==1?"菜单":"操作" }}</td>
                            <td><?php echo empty($value['MenuUrl']) ? '' : $value['MenuUrl']; ?></td>
                            <td>{{ $value['Status']==1?"启用":"禁用" }}</td>
                            <td><?php echo empty($value['Remark']) ? '' : $value['Remark']; ?></td>
                            <td>
                                <a href="">
                                    <button type="button" class="layui-btn layui-btn-sm">编辑</button>
                                </a>
                                <a onclick="return confirm('确定删除？');"
                                   href="">
                                    <button type="button" class="layui-btn layui-btn-danger layui-btn-sm">删除</button>
                                </a>
                            </td>
                        </tr>

                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
