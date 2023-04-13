import { test, expect } from '@playwright/test';
import faker from 'faker';
import AppActions from './helper/AppActions';

var app_action;
test.describe('workflow: ', () => {
  test.beforeEach(async ({ page }) => {
    app_action = new AppActions(page);

    await page.goto('/');
    await app_action.login('farzad', 'password');
  });

  test('add new workflow', async ({ page,request }) => {
    await page.click('text=Workflows');
    await page.click('text=Add Workflow');
    await page.click('input[name="name"]');
    await page.fill('input[name="name"]', 'New Car Sale Workflow');
    await page.click('input:has-text("Create")');

    let payload = {"id":1,"name":"New Car Sale Workflow","description":"","active":true,"edges":[{"source":"dndnode_1","sourceHandle":"b","target":"dndnode_2","targetHandle":"a","id":"reactflow__edge-dndnode_1b-dndnode_2a"},{"source":"dndnode_2","sourceHandle":"b","target":"dndnode_3","targetHandle":"a","id":"reactflow__edge-dndnode_2b-dndnode_3a"},{"source":"dndnode_3","sourceHandle":"b","target":"dndnode_4","targetHandle":"a","id":"reactflow__edge-dndnode_3b-dndnode_4a"},{"source":"dndnode_4","sourceHandle":"b","target":"dndnode_5","targetHandle":"a","id":"reactflow__edge-dndnode_4b-dndnode_5a"},{"source":"dndnode_5","sourceHandle":"b","target":"dndnode_6","targetHandle":"a","id":"reactflow__edge-dndnode_5b-dndnode_6a"},{"source":"dndnode_6","sourceHandle":"b","target":"dndnode_7","targetHandle":"a","id":"reactflow__edge-dndnode_6b-dndnode_7a"}],"nodes":[{"id":"dndnode_1","type":"EditableNodeInput","position":{"x":250,"y":10},"data":{"label":"Start","edit_mode":false}},{"id":"dndnode_2","type":"EditableNodeDefault","position":{"x":250,"y":60},"data":{"label":"Contact Customer","edit_mode":false}},{"id":"dndnode_3","type":"EditableNodeDefault","position":{"x":250,"y":110},"data":{"label":"Test Drive","edit_mode":false}},{"id":"dndnode_4","type":"EditableNodeDefault","position":{"x":250,"y":160},"data":{"label":"Quote","edit_mode":false}},{"id":"dndnode_5","type":"EditableNodeDefault","position":{"x":250,"y":210},"data":{"label":"Sell Car","edit_mode":false}},{"id":"dndnode_6","type":"EditableNodeDefault","position":{"x":250,"y":250},"data":{"label":"Verify All Paperworks","edit_mode":false}},{"id":"dndnode_7","type":"EditableNodeOutput","position":{"x":250,"y":310},"data":{"label":"Deliver Car","edit_mode":false}}]}

    let r = await request.post("/api/v1/workflows/2", {
        data: payload,
    });

    await page.goto('/workflows/2');
  });
});
