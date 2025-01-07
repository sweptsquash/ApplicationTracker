export function useFormatSalaryPeriod(app?: App.Application): string {
  switch (app?.salary_period) {
    case 'hourly':
      return 'per hour'
    case 'daily':
      return 'per day'
    case 'weekly':
      return 'per week'
    case 'monthly':
      return 'per month'
    case 'annually':
      return 'per year'
    default:
      return ''
  }
}
