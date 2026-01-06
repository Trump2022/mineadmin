/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://github.com/mineadmin
 */
import type { MaSearchItem } from '@mineadmin/search'

export default function getSearchItems(t: any): MaSearchItem[] {
  return [
    {
      label: '分类名称',
      prop: 'name',
      render: 'input',
      renderProps: {
        placeholder: '请输入分类名称',
        clearable: true,
      },
    },
    {
      label: '状态',
      prop: 'status',
      render: 'select',
      renderProps: {
        placeholder: '请选择状态',
        clearable: true,
        options: [
          { label: '启用', value: 1 },
          { label: '停用', value: 0 },
        ],
      },
    },
  ]
}
