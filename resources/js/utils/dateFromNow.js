import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'

export default function (date, locale) {
  dayjs.extend(relativeTime)

  if (locale) {
    require(`dayjs/locale/${locale}.js`)
    dayjs.locale(locale)
  }

  return dayjs(date).fromNow()
}
