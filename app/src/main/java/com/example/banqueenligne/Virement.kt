package com.example.banqueenligne

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.View
import android.view.Window

class Virement : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        supportActionBar?.hide()
        setContentView(R.layout.virement)
    }
    fun toMakeVirement(view : View?){
        val intent = Intent(this@Virement, MakeVirement::class.java)
        startActivity(intent)
    }
}