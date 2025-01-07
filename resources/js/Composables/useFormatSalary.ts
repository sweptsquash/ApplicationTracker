export default function useFormatSalary(app?: App.Application): string {
  if (!app) {
    return 'N/A'
  }

  if (app.salary_type === 'unknown') {
    return 'Unknown / Competitive'
  }

  if (app.salary_type === 'range') {
    return `${useFormatCurrency(app.salary_min)} - ${useFormatCurrency(app.salary_max)} ${useFormatSalaryPeriod(app)}`
  }

  return `${useFormatCurrency(app.salary_min)} ${useFormatSalaryPeriod(app)}`
}
