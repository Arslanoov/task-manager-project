const { Then } = require('cucumber')
const { expect } = require('chai')

Then('I see welcome text', async function () {
  await this.page.waitForSelector('[data-test=welcome]')
  const text = await this.page.$eval('[data-test=welcome]', el => el.textContent)
  expect(text).to.eql('Welcome to Furious TODO!')
})
